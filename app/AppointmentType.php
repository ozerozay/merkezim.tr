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
            self::appointment => 'text-green-500 bg-green-100',
            self::close => 'text-gray-500 bg-gray-100',
            self::reservation => 'text-yellow-500 bg-yellow-100',
        };
    }
}
