<?php

namespace App;

enum AppointmentType: string
{
    case appointment = 'appointment';
    case close = 'close';
    case reservation = 'reservation';

    public static function has(string $value): bool
    {
        return collect(self::cases())->contains(fn ($case) => $case->value === $value);
    }

    public function label(): string
    {
        return match ($this) {
            self::appointment => 'Randevu',
            self::close => 'Kapalı',
            self::reservation => 'Rezervasyon',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::appointment => 'badge-success',
            self::close => 'badge-neutral',
            self::reservation => 'badge-purple',
        };
    }
}
