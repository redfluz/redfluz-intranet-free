<?php

declare(strict_types=1);

namespace App\Http\Requests\Personnel\Message;

use App\Enums\UploadType;

/**
 * The fields a message is sent from.
 *
 * A message may carry either a body or an attachment (or both), so the body is
 * required unless an attachment is present. The parent message, when given,
 * threads the new message under an existing one.
 */
class SendMessageRequest extends MessageRequest
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
            'message' => ['nullable', 'string', 'required_without:attachment', 'max:5000'],
            'attachment' => ['nullable', 'file', UploadType::DOCUMENT_AND_IMAGES->rule(), 'max:8000'],
            'parent_id' => ['nullable', 'string', 'exists:messages,id'],
        ];
    }
}
