<?php

declare(strict_types=1);

namespace App\Support\Data\Attendance;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class AttendanceData extends Data
{
    public function __construct(
        #[MapName('clock_in')] public ?CarbonImmutable $clockIn = null,
        #[MapName('clock_out')] public ?CarbonImmutable $clockOut = null,
        #[MapName('worked_minutes')] public ?int $workedMinutes = null,
    ) {}

}
