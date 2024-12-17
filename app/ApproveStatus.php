<?php

namespace App;

enum ApproveStatus: string
{
    case waiting = 'waiting';
    case rejected = 'rejected';
    case approved = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::waiting => 'Bekliyor',
            self::rejected => 'Reddedildi',
            self::approved => 'Onaylandı',
        };
    }
}
