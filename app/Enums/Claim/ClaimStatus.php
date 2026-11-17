<?php

namespace App\Enums\Claim;

enum ClaimStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case PAID = 'paid';
    case REJECTED = 'rejected';
    case POSTED = 'posted';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('claim_status.draft'),
            self::SUBMITTED => __('claim_status.submitted'),
            self::PENDING => __('claim_status.pending'),
            self::APPROVED => __('claim_status.approved'),
            self::PAID => __('claim_status.paid'),
            self::REJECTED => __('claim_status.rejected'),
            self::POSTED => __('claim_status.posted'),
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::DRAFT => 'theme-secondary',
            self::SUBMITTED => 'theme-info',
            self::PENDING => 'theme-warning',
            self::APPROVED => 'theme-primary',
            self::PAID => 'theme-success',
            self::POSTED => 'theme-dark',
            self::REJECTED => 'theme-danger',
        };
    }
}
