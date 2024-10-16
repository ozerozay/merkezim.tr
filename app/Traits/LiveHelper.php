<?php

namespace App\Traits;

use Illuminate\Support\Number;

class LiveHelper
{
    public static function gender_text($id)
    {
        switch ($id) {
            case 0:
                return 'Unisex';
            case 1:
                return 'Kadın';
            case 2:
                return 'Erkek';
        }
    }

    public static function price_text($price)
    {
        return Number::currency((float) $price, in: 'TRY', locale: 'tr');
    }
}
