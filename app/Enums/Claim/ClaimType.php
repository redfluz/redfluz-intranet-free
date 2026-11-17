<?php

namespace App\Enums\Claim;

enum ClaimType: string
{
    case NORMAL = 'normal';
    case PROJECT = 'project';
    case VEHICLE = 'vehicle';
    case CLINIC = 'clinic';
    case CUSTOMER = 'customer';

    public function label(): string
    {
        return match ($this) {
            self::NORMAL => __('claim_type.normal'),
            self::PROJECT => __('claim_type.project'),
            self::VEHICLE => __('claim_type.vehicle'),
            self::CLINIC => __('claim_type.clinic'),
            self::CUSTOMER => __('claim_type.customer'),
        };
    }
}
