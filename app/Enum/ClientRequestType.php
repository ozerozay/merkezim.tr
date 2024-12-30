<?php

namespace App\Enum;

enum ClientRequestType: string
{
    case appointment_cancel = 'appointment_cancel';
    case appointment_create = 'appointment_create';
    case offer_request = 'offer_request';
    case payment_kk = 'payment_kk';
    case payment_havale = 'payment_havale';
    case use_to_earn_gift = 'use_to_earn_gift';
    case referans_gift = 'referans_gift';
    case contact = 'contact';

    public function label(): string
    {
        return match ($this) {
            self::appointment_cancel => 'appointment_cancel',
            self::appointment_create => 'appointment_create',
            self::offer_request => 'offer_request',
            self::payment_kk => 'payment_kk',
            self::payment_havale => 'payment_havale',
            self::use_to_earn_gift => 'use_to_earn_gift',
            self::referans_gift => 'referans_gift',
            self::contact => 'contact',
        };
    }
}
