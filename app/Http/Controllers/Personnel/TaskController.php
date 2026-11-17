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

namespace App\Http\Controllers\Personnel;

use App\Constants\FlashMessage;
use App\Enums\FileAction;
use App\Enums\FileResources;
use App\Http\Controllers\Controller;
use App\Http\Requests\Personnel\Task\StoreTaskRequest;
use App\Http\Requests\Personnel\Task\UpdateTaskRequest;
use App\Http\Requests\Personnel\Task\UpdateTaskStageRequest;
use App\Models\Task\Task;
use App\Services\Personnel\TaskService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * Task / Kanban board controller.
 *
 * Tasks are self-managed: each person creates, edits, closes and deletes their
 * own cards. They are organised as a pipeline (Introduction -> Follow Up 1 ->
 * Follow Up 2 -> Proceed -> Success | Failure) and are never generated from RMA,
 * fixed-asset maintenance or sales documents.
 */
class TaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  TaskService  $taskService  The task service instance.
     */
    public function __construct(private readonly TaskService $taskService) {}

    /**
     * Display a listing of the resource.
     * Display the Kanban board, grouped by pipeline stage.
     *
     * @param  Request  $request  The HTTP request instance containing query filters.
     * @return View The rendered Kanban board Blade view.
     */
    public function index(Request $request): View
    {
        $this->allows(FileResources::TASKS);
        $user = $request->user();
        abort_if($user === null, 401);

        /** @var string|null $search */
        $search = $request->input('search');

        /** @var string|null $priority */
        $priority = $request->input('priority');

        /** @var EloquentCollection<int, Task> $tasks */
        $tasks = $this->taskService->buildBoardQuery($search, $priority, $user->id)
            ->orderBy('position')
            ->get();

        $tasksByStage = $this->taskService->groupTasksByStage($tasks);

        return view('personnel.tasks.index', compact('tasksByStage', 'search', 'priority'));
    }

    /**
     * Display successful and failed tasks.
     *
     * @param  Request  $request  The HTTP request instance containing history filters.
     * @return View Returns the rendered task-history view.
     */
    public function history(Request $request): View
    {
        $this->allows(FileResources::PERSONNEL_TASK_HISTORY);
        abort_if($request->user() === null, 401);

        $search = $request->string('search')->toString() ?: null;
        $priority = $request->string('priority')->toString() ?: null;
        $status = $request->string('status')->toString() ?: null;

        $tasks = $this->taskService->buildHistoryQuery($search, $priority, $status, (string) (auth()->id() ?? ''))
            ->paginate(20)
            ->withQueryString();

        return view('personnel.tasks.history', compact('tasks', 'search', 'priority', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     * Show the form for creating a self-managed task.
     *
     * @return View Returns the rendered Blade view.
     */
    public function create(): View
    {
        $this->allows(FileResources::TASKS, FileAction::ADD);

        return view('personnel.tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     * Store a self-managed task owned by the authenticated user.
     *
     * @param  StoreTaskRequest  $request  The validated HTTP request instance.
     * @return RedirectResponse Redirect response to the task board.
     *
     * @throws ValidationException|Throwable
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $user = $request->user();
        abort_if($user === null, 401);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $user): void {
            $this->taskService->createTask(
                $validated,
                $request->hasFile('attachments') ? $request->file('attachments') : null,
                $user->id,
            );
        });

        return redirect()
            ->route('personnel.tasks.index')
            ->with('success', FlashMessage::created(FileResources::TASKS));
    }

    /**
     * Show the form for editing an open resource.
     * Show the form for editing the specified task.
     *
     * @param  Task  $task  The task model instance.
     * @return View|RedirectResponse Returns the rendered Blade view, or a redirect for a completed task.
     */
    public function edit(Task $task): View|RedirectResponse
    {
        $this->allows(FileResources::TASKS, FileAction::UPDATE);
        abort_unless($task->assigned_to === auth()->id(), 403);

        if ($task->stage->isClosed()) {
            return redirect()
                ->route('personnel.tasks.index')
                ->with('error', __('tasks.completed_task_locked'));
        }

        $task->load('attachments');

        return view('personnel.tasks.edit', compact('task'));
    }

    /**
     * Update an existing resource by its object.
     * Update the specified task.
     *
     * @param  UpdateTaskRequest  $request  The validated HTTP request instance containing updated data.
     * @param  Task  $task  The task model instance.
     * @return RedirectResponse Redirect response to the task board.
     *
     * @throws ValidationException|Throwable
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        abort_unless($task->assigned_to === auth()->id(), 403);

        if ($task->stage->isClosed()) {
            return redirect()
                ->route('personnel.tasks.index')
                ->with('error', __('tasks.completed_task_locked'));
        }

        $validated = $request->validated();

        DB::transaction(function () use ($task, $validated, $request) {
            $this->taskService->updateTask(
                $task,
                $validated,
                $request->hasFile('attachments') ? $request->file('attachments') : null,
                is_array($validated['delete_attachments'] ?? null)
                    ? array_values(array_filter($validated['delete_attachments'], 'is_string'))
                    : null,
            );
        });

        return redirect()
            ->route('personnel.tasks.index')
            ->with('success', FlashMessage::updated(FileResources::TASKS));
    }

    /**
     * Update only the stage (and board position) of a task.
     *
     * Called via AJAX when a card is dropped into a new Kanban column.
     *
     * @param  UpdateTaskStageRequest  $request  The HTTP request instance.
     * @param  Task  $task  The task model instance.
     * @return JsonResponse Returns a JSON status response.
     */
    public function updateStage(UpdateTaskStageRequest $request, Task $task): JsonResponse
    {
        abort_unless($task->assigned_to === auth()->id(), 403);

        if ($task->stage->isClosed()) {
            return response()->json([
                'status' => 'error',
                'message' => __('tasks.completed_task_locked'),
            ], 409);
        }

        /** @var array{stage: string, position: int|null, completion_note?: string|null} $validated */
        $validated = $request->validated();

        $user = $request->user() ?? abort(403);

        $this->taskService->updateStage(
            $task,
            $validated['stage'],
            $validated['position'],
            $validated['completion_note'] ?? null,
            $user->id,
        );

        return response()->json([
            'status' => 'success',
            'message' => FlashMessage::updated(FileResources::TASKS),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * Delete a self-managed task owned by the authenticated user.
     *
     * @param  Task  $task  The task model instance.
     * @return RedirectResponse Redirect response to the task board.
     *
     * @throws Throwable
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->allows(FileResources::TASKS, FileAction::REMOVE);
        abort_unless($task->assigned_to === auth()->id(), 403);

        DB::transaction(function () use ($task): void {
            $this->taskService->deleteTask($task);
        });

        return redirect()
            ->route('personnel.tasks.index')
            ->with('success', FlashMessage::deleted(FileResources::TASKS));
    }
}
