<?php

namespace App\Enum;

enum WebFormStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Beklemede',
            self::APPROVED => 'Onaylandı',
            self::REJECTED => 'Reddedildi',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'error',
        };
    }
}
