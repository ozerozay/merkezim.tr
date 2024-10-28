<?php

namespace App;

enum TaksitStatus: string
{
    case waiting = 'waiting';
    case success = 'success';
    case late_payment = 'late_payment';

    public function label(): string
    {
        return match ($this) {
            self::waiting => 'Bekleniyor',
            self::success => 'Onaylandı',
            self::late_payment => 'Gecikmiş',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::waiting => 'warning',
            self::success => 'success',
            self::late_payment => 'error',
        };
    }
}
