<?php

/**
 * ============================================================================
 *
 * @author redfluz
 *
 * @github https://github.com/redfluz
 *
 * @youtube https://youtube.com/@redfluz
 *
 * @contact corex2020@icloud.com
 *
 * @customer
 *
 * @copyright Copyright (c) 2026 redfluz. All Rights Reserved.
 * ============================================================================
 *
 * PROPRIETARY AND CONFIDENTIAL
 *
 * All code, concepts, and intellectual property contained in this file are the
 * exclusive property of redfluz. Unauthorized copying, distribution, modification,
 * reverse engineering, or public display of this software via any medium is
 * strictly prohibited without explicit written permission from the copyright owner.
 */

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Tells open chat tabs to drop a message bubble without a page reload.
 *
 * "Delete for everyone" removes it from both participants' views; "delete for
 * me" is only removed in tabs belonging to the user who pressed delete.
 */
class MessageDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param  string  $messageId  The deleted message ID.
     * @param  string  $mode  Either "me" or "everyone".
     * @param  string  $userId  The user who performed the deletion.
     * @param  string  $senderId  The message sender, for channel targeting.
     * @param  string  $receiverId  The message receiver, for channel targeting.
     */
    public function __construct(
        public string $messageId,
        public string $mode,
        public string $userId,
        public string $senderId,
        public string $receiverId,
    ) {}

    /**
     * @return array<int, PrivateChannel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->senderId),
            new PrivateChannel('user.'.$this->receiverId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageDeleted';
    }
}
