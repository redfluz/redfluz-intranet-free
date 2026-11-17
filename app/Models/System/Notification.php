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

namespace App\Models\System;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;

/**
 * @mixin Builder<static>
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int|string $notifiable_id
 * @property array<string, mixed> $data
 * @property CarbonInterface|null $read_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property CarbonInterface|null $deleted_at
 * @property-read string|null $title
 * @property-read string|null $message
 *
 * @noinspection PhpUnused
 *
 * @property-read Model|null $notifiable
 */
#[Table('notifications', key: 'id', keyType: 'string', incrementing: false)]
#[Fillable([
    'id',
    'type',
    'notifiable_type',
    'notifiable_id',
    'data',
    'read_at',
])]
class Notification extends DatabaseNotification
{
    use SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'type' => 'string',
            'notifiable_type' => 'string',
            'data' => 'array',
            'read_at' => 'datetime',
        ];
    }

    /**
     * Accessor for the title extracted from the data payload.
     *
     * @return string|null Returns the notification title.
     */
    public function getTitleAttribute(): ?string
    {
        $title = $this->data['title'] ?? null;

        return is_string($title) ? $title : null;
    }

    /**
     * Accessor for the message body extracted from the data payload.
     *
     * @return string|null Returns the notification message.
     */
    public function getMessageAttribute(): ?string
    {
        $message = $this->data['message'] ?? null;

        return is_string($message) ? $message : null;
    }

    /**
     * Scope query to only include unread notifications.
     *
     * @param  Builder<static>  $query  The query builder instance.
     * @return Builder<static> The filtered query builder with only unread notifications.
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope query to only include read notifications.
     *
     * @param  Builder<static>  $query  The query builder instance.
     * @return Builder<static> The filtered query builder with only read notifications.
     */
    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope query to filter by notification class type.
     *
     * @param  Builder<static>  $query  The query builder instance.
     * @param  string  $type  The notification class type.
     * @return Builder<static> The filtered query builder matching the given notification type.
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }
}
