<?php

namespace App\Enums;

/**
 * Document types that use a sequential reference number.
 */
enum DocumentSubType: string
{
    case CLAIM = 'claim';
    case LEAVE = 'leave';
    case TASK = 'task';

    public function label(): string
    {
        return match ($this) {
            self::CLAIM => __('claims.title'),
            self::LEAVE => __('leaves.admin.title'),
            self::TASK => __('tasks.title'),
        };
    }

    public function prefix(): string
    {
        return match ($this) {
            self::CLAIM => 'CLM',
            self::LEAVE => 'LEV',
            self::TASK => 'TASK',
        };
    }
}
