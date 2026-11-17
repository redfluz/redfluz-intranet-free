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

class SystemAlert extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected string $title;

    protected string $message;

    protected string $url;

    protected ?string $userId;

    public function __construct(string $title, string $message, string $url = '#', ?string $userId = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->userId = $userId;
    }

    /**
     * Choose the notification channels.
     *
     * @return list<string> The delivery channels.
     */
    public function via(): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Build the stored notification payload.
     *
     * @return array{title: string, message: string, url: string} The notification data.
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
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
