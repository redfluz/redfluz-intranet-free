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

namespace App\Models\Leave;

use App\Enums\Leave\LeaveStatus;
use App\Enums\Leave\LeaveType;
use App\Models\System\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @mixin Builder<Leave>
 *
 * @property string $id
 * @property string|null $reference_number
 * @property string $user_id
 * @property string|null $approved_by
 * @property string|null $created_by
 * @property LeaveType $type
 * @property CarbonInterface $start_date
 * @property CarbonInterface $end_date
 * @property int $total_days
 * @property string|null $reason
 * @property LeaveStatus $status
 * @property CarbonInterface|null $approved_at
 * @property string|null $rejection_reason
 * @property string|null $attachment_path
 * @property CarbonInterface|null $deleted_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * @noinspection PhpUnused
 *
 * @property-read User|null $employee
 * @property-read User|null $approver
 * @property-read User|null $creator
 * @property-read EloquentCollection<int, Activity> $activities
 * @property-read EloquentCollection<int, Activity> $activitiesAsCauser
 * @property-read EloquentCollection<int, Activity> $activitiesAsSubject
 */
#[Table('leaves', key: 'id')]
#[Fillable([
    'reference_number',
    'user_id',
    'approved_by',
    'created_by',
    'type',
    'start_date',
    'end_date',
    'total_days',
    'reason',
    'status',
    'approved_at',
    'rejection_reason',
    'attachment_path',
])]
class Leave extends Model
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
            'type' => LeaveType::class,
            'start_date' => 'date',
            'end_date' => 'date',
            'total_days' => 'integer',
            'status' => LeaveStatus::class,
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the user who is taking the leave.
     *
     * @return BelongsTo<User, $this>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the user who approved the leave.
     *
     * @return BelongsTo<User, $this>
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the user who created the leave request.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get approved leave records that overlap the current week, excluding
     * the given user.
     *
     * @param  string|null  $excludingUserId  User ID to exclude (typically the logged-in user).
     * @return Collection<int, Leave> Returns matching leave records with their employee loaded.
     */
    public static function onLeaveThisWeek(?string $excludingUserId = null): Collection
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return self::query()
            ->with(['employee'])
            ->where('status', LeaveStatus::APPROVED->value)
            ->when($excludingUserId, fn ($q) => $q->where('user_id', '!=', $excludingUserId))
            ->where('start_date', '<=', $endOfWeek)
            ->where('end_date', '>=', $startOfWeek)
            ->oldest('start_date')
            ->get();
    }

    /**
     * Get approved leave records that overlap a given month.
     *
     * @param  Carbon  $monthStart  First day of the month.
     * @param  Carbon  $monthEnd  Last day of the month.
     * @return Collection<int, Leave> Returns matching leave records with their employee loaded.
     */
    public static function approvedOverlappingRange(Carbon $monthStart, Carbon $monthEnd): Collection
    {
        return self::query()
            ->with('employee')
            ->where('status', LeaveStatus::APPROVED->value)
            ->where('start_date', '<=', $monthEnd)
            ->where('end_date', '>=', $monthStart)
            ->oldest('start_date')
            ->get();
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
