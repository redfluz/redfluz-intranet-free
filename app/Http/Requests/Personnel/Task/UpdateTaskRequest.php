<?php

/**
 * ============================================================================
 *
 * @author      redfluz
 *
 * @github      https://github.com/redfluz
 *
 * @youtube     https://www.youtube.com/@redfluz
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

declare(strict_types=1);

namespace App\Http\Requests\Personnel\Task;

use App\Enums\FileAction;
use App\Enums\FileResources;
use App\Enums\Task\TaskStage;
use App\Enums\UploadType;
use Illuminate\Validation\Rule;

/**
 * The fields a task is edited from.
 *
 * The rules live here rather than in the controller so that the controller can be
 * handed a request that has already been checked. A rule stated once cannot be
 * stated twice in two places that then disagree.
 *
 * Tasks are self-managed in the intranet edition: they carry no company, contact
 * or invoice binding and stay assigned to the person editing them. Closing the
 * task (moving it to success or failure) additionally demands a completion note,
 * which the requiredIf rule enforces against the stage that was actually
 * submitted.
 *
 * Two guards stay in the controller rather than here: that the card is still open
 * and that it belongs to the person editing it. Both describe the state of a
 * specific card, not the shape of the request, and they read more clearly next to
 * the model they act on.
 */
class UpdateTaskRequest extends TaskRequest
{
    /**
     * Who may edit a task.
     *
     * The permission lives here rather than in the controller, so that the request
     * says everything about who may send it and what it must contain. Laravel
     * asks this before the controller runs, so a request that is not permitted
     * never reaches the action it was meant to guard.
     *
     * It refuses by redirecting with an explanation and writing the attempt to
     * the log, which is what this check has always done and what returning a
     * plain false would lose.
     *
     * @return bool Whether the authenticated user may perform this action.
     */
    public function authorize(): bool
    {
        return $this->allows(FileResources::TASKS, FileAction::UPDATE);
    }

    /**
     * The validation rules for editing a task.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $isClosingRequest = in_array($this->input('stage'), [
            TaskStage::SUCCESS->value,
            TaskStage::FAILURE->value,
        ], true);

        return [
            'title' => ['required', 'string', 'max:255'],
            'stage' => ['required', 'in:introduction,follow_up_1,follow_up_2,proceed,success,failure'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'completion_note' => [Rule::requiredIf($isClosingRequest), 'nullable', 'string', 'max:5000'],
            'delete_attachments' => ['nullable', 'array'],
            'delete_attachments.*' => ['exists:task_attachments,id'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', UploadType::DOCUMENT_AND_IMAGES->rule(), 'max:8000'],
        ];
    }
}
