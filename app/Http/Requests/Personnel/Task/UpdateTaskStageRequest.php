<?php

declare(strict_types=1);

namespace App\Http\Requests\Personnel\Task;

use App\Enums\FileAction;
use App\Enums\FileResources;
use App\Enums\Task\TaskStage;
use Illuminate\Validation\Rule;

/**
 * Guards moving a self-service task to a different stage.
 *
 * Completion_note is required when the submitted stage is a closed stage
 * (success or failure). The controller additionally checks that the task is
 * still assigned to the signed-in user and is not already closed.
 */
class UpdateTaskStageRequest extends TaskRequest
{
    public function authorize(): bool
    {
        return $this->allows(FileResources::TASKS, FileAction::UPDATE);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $isClosingRequest = in_array($this->input('stage'), [
            TaskStage::SUCCESS->value,
            TaskStage::FAILURE->value,
        ], true);

        return [
            'stage' => ['required', 'in:introduction,follow_up_1,follow_up_2,proceed,success,failure'],
            'position' => ['nullable', 'integer', 'min:0'],
            'completion_note' => [Rule::requiredIf($isClosingRequest), 'nullable', 'string', 'max:5000'],
        ];
    }
}
