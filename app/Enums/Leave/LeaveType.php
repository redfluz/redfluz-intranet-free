<?php

namespace App\Enums\Leave;

enum LeaveType: string
{
    case ANNUAL = 'annual';
    case SICK = 'sick';
    case CASUAL = 'casual';
    case HOSPITAL = 'hospitalisation';
    case MATERNITY = 'maternity';
    case PATERNITY = 'paternity';
    case COMPASSION = 'compassionate';
    case MARRIAGE = 'marriage';
    case UNPAID = 'unpaid';

    public function label(): string
    {
        return match ($this) {
            self::ANNUAL => __('leave_type.annual'),
            self::SICK => __('leave_type.sick'),
            self::CASUAL => __('leave_type.casual'),
            self::HOSPITAL => __('leave_type.hospital'),
            self::MATERNITY => __('leave_type.maternity'),
            self::PATERNITY => __('leave_type.paternity'),
            self::COMPASSION => __('leave_type.compassionate'),
            self::MARRIAGE => __('leave_type.marriage'),
            self::UNPAID => __('leave_type.unpaid'),
        };
    }

    public function shortCode(): string
    {
        return match ($this) {
            self::ANNUAL => 'AL',
            self::SICK => 'SL',
            self::CASUAL => 'CL',
            self::HOSPITAL => 'HS',
            self::MATERNITY => 'M',
            self::PATERNITY => 'P',
            self::COMPASSION => 'C',
            self::MARRIAGE => 'MRG',
            self::UNPAID => 'U',
        };
    }

    public function entitlement(): int
    {
        return match ($this) {
            self::ANNUAL => 20,
            self::SICK => 10,
            self::CASUAL, self::HOSPITAL, self::MATERNITY, self::PATERNITY, self::COMPASSION, self::MARRIAGE => 5,
            self::UNPAID => 0,
        };
    }

    public function iconClass(): string
    {
        return match ($this) {
            self::ANNUAL, self::UNPAID => 'bg-primary-subtle text-primary',
            self::SICK => 'bg-success-subtle text-success',
            self::CASUAL, self::HOSPITAL, self::MATERNITY, self::PATERNITY, self::COMPASSION, self::MARRIAGE => 'bg-info-subtle text-info',
        };
    }

    public function progressBarClass(): string
    {
        return match ($this) {
            self::ANNUAL => 'bg-primary',
            self::SICK => 'bg-success',
            self::CASUAL, self::HOSPITAL, self::MATERNITY, self::PATERNITY, self::COMPASSION, self::MARRIAGE => 'bg-info',
            self::UNPAID => 'bg-warning',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ANNUAL => 'bg-primary',
            self::SICK => 'bg-success',
            self::CASUAL, self::MATERNITY, self::PATERNITY, self::COMPASSION, self::MARRIAGE => 'bg-info',
            self::HOSPITAL => 'bg-warning',
            self::UNPAID => 'bg-danger',
        };
    }
}
