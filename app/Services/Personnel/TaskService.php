<?php

/**
 * ============================================================================
 *
 * @author      redfluz
 *
 * @github      https://github.com/redfluz
 *
 * @youtube     https://youtube.com/@redfluz
 *
 * @contact     corex2020@icloud.com
 *
 * @customer
 *
 * @copyright   Copyright (c) 2026 redfluz. All Rights Reserved.
 * ============================================================================
 *
 * PROPRIETARY AND CONFIDENTIAL
 *
 * All code, concepts, and intellectual property contained in this file are the
 * exclusive property of redfluz. Unauthorized copying, distribution, modification,
 * reverse engineering, or public display of this software via any medium is
 * strictly prohibited without explicit written permission from the copyright owner.
 */

namespace App\Services\Personnel;

use App\Enums\DocumentSubType;
use App\Enums\Task\TaskNoteType;
use App\Enums\Task\TaskStage;
use App\Models\Task\Task;
use App\Models\Task\TaskAttachment;
use App\Models\Task\TaskNote;
use App\Traits\GeneratesDocumentNumber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Self-managed personnel tasks.
 *
 * Tasks in the intranet edition are created and maintained by the person who
 * works on them. They are never generated from RMA, fixed-asset maintenance or
 * sales documents, and carry no company, contact or invoice binding.
 */
class TaskService
{
    use GeneratesDocumentNumber;

    /**
     * Build the filtered Task query shared by the board view.
     *
     * @param  string|null  $search  The search term.
     * @param  string|null  $priority  The priority filter.
     * @param  string  $assignedTo  The assigned user ID.
     * @return Builder<Task> The query builder.
     */
    public function buildBoardQuery(?string $search, ?string $priority, string $assignedTo): Builder
    {
        return $this->filteredTaskQuery($search, $priority, $assignedTo)
            ->where(function (Builder $query): void {
                $historyCutoff = now()->subDays(30);

                $query->whereNotIn('stage', [TaskStage::SUCCESS->value, TaskStage::FAILURE->value])
                    ->orWhere(function (Builder $recentCompleted) use ($historyCutoff): void {
                        $recentCompleted
                            ->whereIn('stage', [TaskStage::SUCCESS->value, TaskStage::FAILURE->value])
                            ->where(function (Builder $completedAt) use ($historyCutoff): void {
                                $completedAt
                                    ->where('closed_at', '>=', $historyCutoff)
                                    ->orWhere(function (Builder $legacyCompleted) use ($historyCutoff): void {
                                        $legacyCompleted
                                            ->whereNull('closed_at')
                                            ->where('updated_at', '>=', $historyCutoff);
                                    });
                            });
                    });
            });
    }

    /**
     * Build the task history query for completed/failed tasks.
     *
     * @param  string|null  $search  The search term.
     * @param  string|null  $priority  The priority filter.
     * @param  string|null  $status  The status filter.
     * @param  string  $assignedTo  The assigned user ID.
     * @return Builder<Task> The query builder.
     */
    public function buildHistoryQuery(?string $search, ?string $priority, ?string $status, string $assignedTo): Builder
    {
        return $this->filteredTaskQuery($search, $priority, $assignedTo)
            ->whereIn('stage', [TaskStage::SUCCESS->value, TaskStage::FAILURE->value])
            ->when(
                in_array($status, [TaskStage::SUCCESS->value, TaskStage::FAILURE->value], true),
                fn (Builder $query) => $query->where('stage', $status)
            )
            ->orderByRaw('COALESCE(closed_at, updated_at) DESC');
    }

    /**
     * Group tasks by their stage.
     *
     * @param  EloquentCollection<int, Task>  $tasks  The tasks collection.
     * @return Collection<string, EloquentCollection<int, Task>> Tasks grouped by stage.
     */
    public function groupTasksByStage(EloquentCollection $tasks): Collection
    {
        return collect(TaskStage::cases())
            ->mapWithKeys(fn (TaskStage $stage) => [
                $stage->value => $tasks->where('stage', $stage->value)->values(),
            ]);
    }

    /**
     * Create a task owned by the given user.
     *
     * @param  array<string, mixed>  $validated  The validated data.
     * @param  array<int, UploadedFile>|null  $attachments  New attachment files.
     * @param  string  $userId  The user who owns and created the task.
     * @return Task The created task.
     *
     * @throws ValidationException
     */
    public function createTask(array $validated, ?array $attachments, string $userId): Task
    {
        $newFilesCount = isset($attachments) ? count($attachments) : 0;

        if ($newFilesCount > 5) {
            throw ValidationException::withMessages([
                'attachments' => ['Total attachments cannot exceed 5 files.'],
            ]);
        }

        $stage = is_string($validated['stage'] ?? null)
            ? TaskStage::tryFrom($validated['stage'])
            : null;

        if ($stage === null) {
            throw ValidationException::withMessages([
                'stage' => ['The selected stage is invalid.'],
            ]);
        }

        $task = Task::query()->create([
            'reference_number' => $this->generateDocumentNumber(DocumentSubType::TASK),
            'title' => $validated['title'],
            'assigned_to' => $userId,
            'created_by' => $userId,
            'stage' => $stage->value,
            'priority' => $validated['priority'],
            'position' => 0,
            'due_date' => $validated['due_date'] ?? null,
            'closed_at' => $stage->isClosed() ? now() : null,
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($stage->isClosed()) {
            TaskNote::query()->create([
                'task_id' => $task->id,
                'created_by' => $userId,
                'type' => $stage === TaskStage::SUCCESS
                    ? TaskNoteType::COMPLETION->value
                    : TaskNoteType::FAILURE->value,
                'note' => trim(is_string($validated['completion_note'] ?? null) ? $validated['completion_note'] : ''),
            ]);
        }

        if (! empty($attachments)) {
            $this->uploadAttachments($task, $attachments);
        }

        return $task;
    }

    /**
     * Update a task with full details and attachments.
     *
     * @param  Task  $task  The task to update.
     * @param  array<string, mixed>  $validated  The validated data.
     * @param  array<int, UploadedFile>|null  $newAttachments  New attachment files.
     * @param  array<int, string>|null  $deleteAttachmentIds  Attachment IDs to delete.
     * @return void Returns nothing after updating the task and its attachments.
     *
     * @throws ValidationException
     */
    public function updateTask(Task $task, array $validated, ?array $newAttachments = null, ?array $deleteAttachmentIds = null): void
    {
        $existingCount = $task->attachments()->count();
        $deleteCount = isset($deleteAttachmentIds) ? count($deleteAttachmentIds) : 0;
        $newFilesCount = isset($newAttachments) ? count($newAttachments) : 0;

        if (($existingCount - $deleteCount + $newFilesCount) > 5) {
            throw ValidationException::withMessages([
                'attachments' => ['Total attachments cannot exceed 5 files.'],
            ]);
        }

        $stageChangedToClosed = $validated['stage'] !== $task->stage->value
            && in_array($validated['stage'], ['success', 'failure'], true);

        $stageChangedToOpen = $validated['stage'] !== $task->stage->value
            && ! in_array($validated['stage'], ['success', 'failure'], true);

        $task->fill([
            'title' => $validated['title'],
            'stage' => $validated['stage'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($stageChangedToClosed) {
            $task->closed_at = now();
        } elseif ($stageChangedToOpen) {
            $task->closed_at = null;
        }

        $task->save();

        if ($stageChangedToClosed) {
            TaskNote::query()->create([
                'task_id' => $task->id,
                'created_by' => auth()->id(),
                'type' => $validated['stage'] === TaskStage::SUCCESS->value
                    ? TaskNoteType::COMPLETION->value
                    : TaskNoteType::FAILURE->value,
                'note' => trim(is_string($validated['completion_note'] ?? null) ? $validated['completion_note'] : ''),
            ]);
        }

        if (! empty($deleteAttachmentIds)) {
            $this->deleteAttachments($task, $deleteAttachmentIds);
        }

        if (! empty($newAttachments)) {
            $this->uploadAttachments($task, $newAttachments);
        }
    }

    /**
     * Update only the stage and position of a task (for kanban drag).
     *
     * @param  Task  $task  The task to update.
     * @param  string  $stage  The new stage.
     * @param  int|null  $position  The new position.
     * @param  string|null  $completionNote  The completion note if closing.
     * @param  string|null  $userId  The user performing the action.
     * @return void Returns nothing after updating the stage and position.
     */
    public function updateStage(Task $task, string $stage, ?int $position, ?string $completionNote, ?string $userId): void
    {
        $isClosedStage = in_array($stage, ['success', 'failure'], true);
        $wasClosedStage = $task->stage->isClosed();

        $task->stage = TaskStage::from($stage);
        $task->position = $position ?? $task->position;

        if ($isClosedStage && ! $wasClosedStage) {
            $task->closed_at = now();
        } elseif (! $isClosedStage && $wasClosedStage) {
            $task->closed_at = null;
        }

        $task->save();

        if ($isClosedStage && ! $wasClosedStage) {
            TaskNote::query()->create([
                'task_id' => $task->id,
                'created_by' => $userId,
                'type' => $stage === TaskStage::SUCCESS->value
                    ? TaskNoteType::COMPLETION->value
                    : TaskNoteType::FAILURE->value,
                'note' => trim($completionNote ?? ''),
            ]);
        }
    }

    /**
     * Delete a task together with its notes and stored attachments.
     *
     * @param  Task  $task  The task to delete.
     * @return void Returns nothing after deleting the task and its children.
     */
    public function deleteTask(Task $task): void
    {
        /** @var TaskAttachment $attachment */
        foreach ($task->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }

        $task->workNotes()->delete();
        $task->delete();
    }

    /**
     * Build the current user's task query with the shared board/history filters.
     *
     * @param  string|null  $search  The search term.
     * @param  string|null  $priority  The priority filter.
     * @param  string  $assignedTo  The assigned user ID.
     * @return Builder<Task> The filtered query builder for the current user's tasks.
     */
    private function filteredTaskQuery(?string $search, ?string $priority, string $assignedTo): Builder
    {
        return Task::query()
            ->with(['assignee'])
            ->where('assigned_to', $assignedTo)
            ->when(is_string($search) && $search !== '', function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('title', 'like', "%$search%")
                        ->orWhere('reference_number', 'like', "%$search%");
                });
            })
            ->when(is_string($priority) && $priority !== '', fn ($q) => $q->where('priority', $priority));
    }

    /**
     * Delete flagged attachments from a task.
     *
     * @param  Task  $task  The task instance.
     * @param  array<int, string>  $attachmentIds  The attachment IDs to delete.
     * @return void Returns nothing after deleting the flagged attachments.
     */
    private function deleteAttachments(Task $task, array $attachmentIds): void
    {
        $attachmentsToDelete = TaskAttachment::query()
            ->whereIn('id', $attachmentIds)
            ->where('task_id', $task->id)
            ->get();
        /** @var TaskAttachment $att */
        foreach ($attachmentsToDelete as $att) {
            Storage::disk('public')->delete($att->file_path);
            $att->delete();
        }
    }

    /**
     * Upload new attachments to a task.
     *
     * @param  Task  $task  The task instance.
     * @param  array<int, UploadedFile>  $files  The files to upload.
     * @return void Returns nothing after uploading the new attachments.
     */
    private function uploadAttachments(Task $task, array $files): void
    {
        foreach ($files as $file) {
            if ($file->isValid()) {
                $path = $file->store('task-attachments', 'public');

                TaskAttachment::query()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'task_id' => $task->id,
                ]);
            }
        }
    }
}
