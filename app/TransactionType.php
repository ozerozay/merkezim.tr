<?php

namespace App;

enum TransactionType: string
{
    case pesinat = 'pesinat';
    case staff_pay = 'staff_pay';
    case adisyon_pesinat = 'adisyon_pesinat';
    case product_pesinat = 'product_pesinat';
    case mahsup = 'mahsup';
    case payment_client = 'payment_client';
    case payment_staff = 'payment_staff';
    case payment_masraf = 'payment_masraf';
    case tahsilat = 'tahsilat';
    case tahsilat_taksit = 'tahsilat_taksit';

    public function label(): string
    {
        return match ($this) {
            self::pesinat => 'Peşinat',
            self::staff_pay => 'Personel Ödeme',
            self::adisyon_pesinat => 'Adisyon Peşinat',
            self::product_pesinat => 'Ürün Peşinat',
            self::mahsup => 'Mahsup',
            self::payment_client => 'Danışan',
            self::payment_staff => 'Personel',
            self::payment_masraf => 'Masraf',
            self::tahsilat => 'Tahsilat',
            self::tahsilat_taksit => 'Tahsilat Taksit',
        };
    }
}
