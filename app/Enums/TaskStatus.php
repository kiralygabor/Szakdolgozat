<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Open = 'open';
    case Assigned = 'assigned';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Assigned => 'Assigned',
            self::Completed => 'Completed',
        };
    }
}
