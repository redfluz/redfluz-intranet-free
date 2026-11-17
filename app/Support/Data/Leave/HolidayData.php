<?php

declare(strict_types=1);

namespace App\Support\Data\Leave;

use App\Enums\Leave\HolidayType;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

final class HolidayData extends Data
{
    public function __construct(public ?string $name = null, public ?CarbonImmutable $date = null, public ?HolidayType $type = null, public ?string $createdBy = null) {}

}
