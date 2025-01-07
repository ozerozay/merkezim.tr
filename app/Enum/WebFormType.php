<?php

namespace App\Enum;

enum WebFormType: string
{
    case APPOINTMENT = 'appointment';
    case PAYMENT = 'payment';
    case REFUND = 'refund';
    case CANCEL = 'cancel';
    case RESCHEDULE = 'reschedule';
    case PACKAGE_UPGRADE = 'package_upgrade';
    case PACKAGE_TRANSFER = 'package_transfer';
    case INSTALLMENT_CHANGE = 'installment_change';
    case PRICE_CHANGE = 'price_change';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::APPOINTMENT => 'Randevu İsteği',
            self::PAYMENT => 'Ödeme İsteği',
            self::REFUND => 'İade İsteği',
            self::CANCEL => 'İptal İsteği',
            self::RESCHEDULE => 'Randevu Değişikliği',
            self::PACKAGE_UPGRADE => 'Paket Yükseltme',
            self::PACKAGE_TRANSFER => 'Paket Transfer',
            self::INSTALLMENT_CHANGE => 'Taksit Değişikliği',
            self::PRICE_CHANGE => 'Fiyat Değişikliği',
            self::OTHER => 'Diğer',
        };
    }
}
