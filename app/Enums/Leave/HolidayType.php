<?php

namespace App\Enums\Leave;

enum HolidayType: string
{
    case PUBLIC = 'public';
    case COMPANY = 'company';
    case STATE = 'state';

    public function label(): string
    {
        return match ($this) {
            self::PUBLIC => __('holiday_type.public'),
            self::COMPANY => __('holiday_type.company'),
            self::STATE => __('holiday_type.state'),
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PUBLIC => 'bg-danger-subtle text-danger',
            self::COMPANY => 'bg-secondary-subtle text-secondary',
            self::STATE => 'bg-primary-subtle text-primary',
        };
    }
}
