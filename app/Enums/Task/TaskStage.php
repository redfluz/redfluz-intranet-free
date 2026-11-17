<?php

namespace App\Enums\Task;

/**
 * @phpstan-impure
 */
enum TaskStage: string
{
    case INTRODUCTION = 'introduction';
    case FOLLOW_UP_1 = 'follow_up_1';
    case FOLLOW_UP_2 = 'follow_up_2';
    case PROCEED = 'proceed';
    case SUCCESS = 'success';
    case FAILURE = 'failure';

    public function label(): string
    {
        return match ($this) {
            self::INTRODUCTION => __('task_stage.introduction'),
            self::FOLLOW_UP_1 => __('task_stage.follow_up_1'),
            self::FOLLOW_UP_2 => __('task_stage.follow_up_2'),
            self::PROCEED => __('task_stage.proceed'),
            self::SUCCESS => __('task_stage.success'),
            self::FAILURE => __('task_stage.failure'),
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::INTRODUCTION => 'bg-secondary',
            self::FOLLOW_UP_1 => 'bg-primary',
            self::FOLLOW_UP_2 => 'bg-info',
            self::PROCEED => 'bg-warning text-dark',
            self::SUCCESS => 'bg-success',
            self::FAILURE => 'bg-danger',
        };
    }

    public function isClosed(): bool
    {
        return $this === self::SUCCESS || $this === self::FAILURE;
    }

    /**
     * @return array<int, string>
     */
    public static function ordered(): array
    {
        return array_map(fn (self $stage) => $stage->value, self::cases());
    }
}
