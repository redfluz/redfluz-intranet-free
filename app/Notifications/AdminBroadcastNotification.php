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

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Notification;

class AdminBroadcastNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public ?string $userId = null
    ) {}

    /**
     * Get the notification channels.
     *
     * @return list<string> The delivery channels.
     */
    public function via(): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Build the notification payload.
     *
     * @return array{title: string, message: string} The notification data.
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
        ];
    }

    /**
     * Get the broadcast channels.
     *
     * @return array<PrivateChannel> The broadcast channels.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->userId),
        ];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string The event name.
     */
    public function broadcastAs(): string
    {
        return 'NotificationReceived';
    }
}
