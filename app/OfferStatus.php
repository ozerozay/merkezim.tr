<?php

namespace App;

enum OfferStatus: string
{
    case waiting = 'waiting';
    case success = 'success';
    case cancel = 'cancel';

    public function label(): string
    {
        return match ($this) {
            self::waiting => 'Bekleniyor',
            self::success => 'Onaylandı',
            self::cancel => 'İptal',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::waiting => 'warning',
            self::success => 'success',
            self::cancel => 'error',
        };
    }
}
