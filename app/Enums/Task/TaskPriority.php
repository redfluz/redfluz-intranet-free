<?php

namespace App\Enums\Task;

enum TaskPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public function label(): string
    {
        return match ($this) {
            self::LOW => __('task_priority.low'),
            self::MEDIUM => __('task_priority.medium'),
            self::HIGH => __('task_priority.high'),
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::LOW => 'bg-secondary',
            self::MEDIUM => 'bg-warning text-dark',
            self::HIGH => 'bg-danger',
        };
    }
}
