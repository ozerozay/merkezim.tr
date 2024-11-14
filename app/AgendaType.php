<?php

namespace App;

enum AgendaType: string
{
    case appointment = 'appointment';
    case reminder = 'reminder';
    case payment = 'payment';

    public static function has(string $value): bool
    {
        return collect(self::cases())->contains(fn ($case) => $case->value === $value);
    }

    public function label(): string
    {
        return match ($this) {
            self::appointment => 'Randevu',
            self::reminder => 'Hatırlatma',
            self::payment => 'Ödeme',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::appointment => 'badge-success',
            self::reminder => 'badge-primary',
            self::payment => 'badge-error',
        };
    }
}
