<?php

namespace App;

enum TalepType: string
{
    case facebook = 'facebook';
    case tiktok = 'tiktok';
    case instagram = 'instagram';
    case google = 'google';
    case website = 'website';
    case referans = 'referans';
    case diger = 'diger';

    public function label(): string
    {
        return match ($this) {
            self::facebook => 'Facebook',
            self::tiktok => 'Tiktok',
            self::instagram => 'Instagram',
            self::google => 'Google',
            self::website => 'Website',
            self::referans => 'Referans',
            self::diger => 'Diğer',
        };
    }
}
