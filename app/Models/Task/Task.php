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

namespace App\Models\Task;

use App\Enums\Task\TaskPriority;
use App\Enums\Task\TaskStage;
use App\Models\System\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @mixin Builder<Task>
 *
 * @property string $id
 * @property string|null $reference_number
 * @property string|null $task_template_id
 * @property string|null $source_type
 * @property string|null $source_id
 * @property string $title
 * @property string|null $assigned_to
 * @property string|null $created_by
 * @property TaskStage $stage
 * @property TaskPriority $priority
 * @property int $position
 * @property CarbonInterface|null $due_date
 * @property CarbonInterface|null $closed_at
 * @property string|null $notes
 * @property CarbonInterface|null $deleted_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * @noinspection PhpUnused
 *
 * @property-read User|null $assignee
 * @property-read User|null $creator
 * @property-read Collection<int, TaskAttachment> $attachments
 * @property-read Collection<int, TaskNote> $workNotes
 * @property-read Collection<int, Activity> $activities
 * @property-read Collection<int, Activity> $activitiesAsCauser
 * @property-read Collection<int, Activity> $activitiesAsSubject
 */
#[Table('tasks', key: 'id')]
#[Fillable([
    'reference_number',
    'task_template_id',
    'source_type',
    'source_id',
    'title',
    'assigned_to',
    'created_by',
    'stage',
    'priority',
    'position',
    'due_date',
    'closed_at',
    'notes',
])]
class Task extends Model
{
    use HasActivity, HasUuids, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'task_template_id' => 'string',
            'source_type' => 'string',
            'source_id' => 'string',
            'title' => 'string',
            'assigned_to' => 'string',
            'created_by' => 'string',
            'stage' => TaskStage::class,
            'priority' => TaskPriority::class,
            'position' => 'integer',
            'due_date' => 'datetime',
            'closed_at' => 'datetime',
            'notes' => 'string',
        ];
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the user this task is assigned to.
     *
     * @return BelongsTo<User, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the user who created this task.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Define a one-to-many relationship.
     * Get the attachments for this task.
     *
     * @return HasMany<TaskAttachment, $this>
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    /**
     * Define a one-to-many relationship.
     * Get the work notes for this task.
     *
     * @return HasMany<TaskNote, $this>
     */
    public function workNotes(): HasMany
    {
        return $this->hasMany(TaskNote::class)->oldest();
    }

    /**
     * Get the options for logging activity using Spatie Activitylog.
     *
     * @return LogOptions Returns configured log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
}
