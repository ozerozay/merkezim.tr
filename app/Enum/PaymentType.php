<?php

namespace App\Enum;

enum PaymentType: string
{
    case taksit = 'taksit';
    case offer = 'offer';
    case tip = 'tip';
    case cart = 'cart';

    public function label(): string
    {
        return match ($this) {
            self::taksit => 'Taksit Ödemesi',
            self::offer => 'Teklif',
            self::tip => 'Bahşiş',
            self::cart => 'Sepet Ödemesi',
        };
    }
}
