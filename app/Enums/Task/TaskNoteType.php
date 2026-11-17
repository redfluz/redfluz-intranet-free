<?php

namespace App\Enums\Task;

enum TaskNoteType: string
{
    case PROGRESS = 'progress';
    case PARTS_REQUIRED = 'parts_required';
    case COMPLETION = 'completion';
    case FAILURE = 'failure';

    public function badgeClass(): string
    {
        return match ($this) {
            self::PROGRESS => 'bg-info text-dark',
            self::PARTS_REQUIRED => 'bg-warning text-dark',
            self::COMPLETION => 'bg-success',
            self::FAILURE => 'bg-danger',
        };
    }
}
