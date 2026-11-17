<?php

namespace App\Enums\Leave;

enum LeaveStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('leave_status.draft'),
            self::SUBMITTED => __('leave_status.submitted'),
            self::PENDING => __('leave_status.pending'),
            self::APPROVED => __('leave_status.approved'),
            self::REJECTED => __('leave_status.rejected'),
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
            self::SUBMITTED, self::PENDING => 'bg-warning-subtle text-warning border border-warning-subtle',
            self::APPROVED => 'bg-success-subtle text-success border border-success-subtle',
            self::REJECTED => 'bg-danger-subtle text-danger border border-danger-subtle',
        };
    }
}
