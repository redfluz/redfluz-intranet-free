<?php

declare(strict_types=1);

namespace App\Http\Requests\Personnel\Message;

use App\Enums\MessageDeletionMode;
use Illuminate\Validation\Rule;

/**
 * The mode sent by the chat delete dialog.
 */
class DeleteMessageRequest extends MessageRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'mode' => ['required', Rule::enum(MessageDeletionMode::class)],
        ];
    }
}
