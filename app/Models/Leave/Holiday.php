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

use App\Enums\Leave\HolidayType;
use App\Models\System\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @mixin Builder<Holiday>
 *
 * @property string $id
 * @property string $name
 * @property CarbonInterface $date
 * @property HolidayType $type
 * @property string|null $created_by
 * @property CarbonInterface|null $deleted_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * @noinspection PhpUnused
 *
 * @property-read User|null $creator
 * @property-read EloquentCollection<int, Activity> $activities
 * @property-read EloquentCollection<int, Activity> $activitiesAsCauser
 * @property-read EloquentCollection<int, Activity> $activitiesAsSubject
 */
#[Table('holidays', key: 'id')]
#[Fillable(['name', 'date', 'type', 'created_by'])]
class Holiday extends Model
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
            'date' => 'date',
            'type' => HolidayType::class,
        ];
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the user who created the holiday.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Determine whether the given date is a registered holiday.
     *
     * @param  Carbon  $date  The date to check.
     * @return bool True when the date matches a holiday record.
     */
    public static function isHoliday(Carbon $date): bool
    {
        return self::query()->whereDate('date', $date->toDateString())->exists();
    }

    /**
     * Get all holiday dates that fall within an (inclusive) date range,
     * keyed by "Y-m-d" for fast lookups.
     *
     * @param  Carbon  $start  Range start (inclusive).
     * @param  Carbon  $end  Range end (inclusive).
     * @return Collection<string, Holiday> Returns holidays keyed by date string.
     */
    public static function between(Carbon $start, Carbon $end): Collection
    {
        return self::query()
            ->whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<=', $end->toDateString())
            ->get()
            ->keyBy(fn (Holiday $holiday) => $holiday->date->toDateString());
    }

    /**
     * Count the number of working days (excludes Saturdays, Sundays, and
     * registered holidays) within an inclusive date range.
     *
     * @param  Carbon  $start  Range start (inclusive).
     * @param  Carbon  $end  Range end (inclusive).
     * @return int Number of working days in the range.
     */
    public static function workingDaysBetween(Carbon $start, Carbon $end): int
    {
        $holidayDates = self::between($start, $end)->keys();

        $count = 0;
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            if (! $cursor->isWeekend() && ! $holidayDates->contains($cursor->toDateString())) {
                $count++;
            }

            $cursor->addDay();
        }

        return $count;
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
