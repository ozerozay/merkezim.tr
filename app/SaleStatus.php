<?php

namespace App;

enum SaleStatus: string
{
    case waiting = 'waiting';
    case success = 'success';
    case cancel = 'cancel';
    case freeze = 'freeze';
    case expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::waiting => 'Bekleniyor',
            self::success => 'Aktif',
            self::cancel => 'İptal',
            self::freeze => 'Donduruldu',
            self::expired => 'Süresi Doldu',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::waiting => 'warning',
            self::success => 'success',
            self::cancel => 'error',
            self::freeze => 'primary',
            self::expired => 'secondary',
        };
    }
}
