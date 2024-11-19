<?php

namespace App;

enum AgendaStatus: string
{
    case waiting = 'waiting';
    case success = 'success';
    case error = 'error';

    public static function has(string $value): bool
    {
        return collect(self::cases())->contains(fn ($case) => $case->value === $value);
    }

    public function label(): string
    {
        return match ($this) {
            self::waiting => 'Bekliyor',
            self::success => 'Olumlu',
            self::error => 'Olumsuz',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::waiting => 'warning',
            self::success => 'success',
            self::error => 'error',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::waiting => 'tabler.hourglass-empty',
            self::success => 'o-check',
            self::error => 'tabler.x',
        };
    }
}
