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
 * The fields a new self-managed task is created from.
 *
 * Tasks in the intranet edition are created by the person who will work on them:
 * there is no company, contact or invoice binding and no assignment to anybody
 * else. The controller stamps the owner onto the card after validation.
 */
class StoreTaskRequest extends TaskRequest
{
    /**
     * Who may create a task.
     *
     * @return bool Whether the authenticated user may perform this action.
     */
    public function authorize(): bool
    {
        return $this->allows(FileResources::TASKS, FileAction::ADD);
    }

    /**
     * The validation rules for creating a task.
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
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', UploadType::DOCUMENT_AND_IMAGES->rule(), 'max:8000'],
        ];
    }
}
