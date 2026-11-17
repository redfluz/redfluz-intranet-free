<?php

declare(strict_types=1);

namespace App\Http\Requests\System\SystemNotification;

use App\Enums\FileAction;
use App\Enums\FileResources;

/**
 * Guards sending a system broadcast notification.
 */
class StoreSystemNotificationRequest extends SystemNotificationRequest
{
    public function authorize(): bool
    {
        return $this->allows(FileResources::SYSTEM_NOTIFICATIONS, FileAction::ADD);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'users.required' => 'Please select at least one recipient.',
        ];
    }
}
