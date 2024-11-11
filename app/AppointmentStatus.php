<?php

namespace App;

enum AppointmentStatus: string
{
    case awaiting_approve = 'awaiting_approve';
    case waiting = 'waiting';
    case confirmed = 'confirmed';
    case rejected = 'rejected';
    case cancel = 'cancel';
    case merkez = 'merkez';
    case late = 'late';
    case forwarded = 'forwarded';
    case finish = 'finish';

    public static function has(string $value): bool
    {
        return collect(self::cases())->contains(fn ($case) => $case->value === $value);
    }

    public function label(): string
    {
        return match ($this) {
            self::waiting => 'Bekliyor',
            self::awaiting_approve => 'Onay Bekliyor',
            self::confirmed => 'Onaylı',
            self::rejected => 'Onaylanmadı',
            self::cancel => 'İptal',
            self::merkez => 'Merkezde',
            self::late => 'Gecikti',
            self::forwarded => 'Yönlendirildi',
            self::finish => 'Bitti'
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::waiting => 'badge badge-warning text-yellow-500 bg-yellow-100',          // Bekliyor
            self::awaiting_approve => 'badge badge-info text-blue-500 bg-blue-100',        // Onay Bekliyor
            self::confirmed => 'badge badge-success text-green-500 bg-green-100',          // Onaylı
            self::rejected => 'badge badge-error text-red-500 bg-red-100',                 // Onaylanmadı
            self::cancel => 'badge badge-neutral text-gray-500 bg-gray-100',               // İptal
            self::merkez => 'badge badge-secondary text-purple-500 bg-purple-100',         // Merkezde
            self::late => 'badge badge-warning text-orange-500 bg-orange-100',             // Gecikti
            self::forwarded => 'badge badge-info text-indigo-500 bg-indigo-100',           // Yönlendirildi
            self::finish => 'badge badge-success text-teal-500 bg-teal-100',               // Bitti
        };
        /*
         * [
    self::waiting => 'badge badge-warning text-yellow-500 bg-yellow-100',          // Bekliyor
    self::awaiting_approve => 'badge badge-info text-blue-500 bg-blue-100',        // Onay Bekliyor
    self::confirmed => 'badge badge-success text-green-500 bg-green-100',          // Onaylı
    self::rejected => 'badge badge-error text-red-500 bg-red-100',                 // Onaylanmadı
    self::cancel => 'badge badge-neutral text-gray-500 bg-gray-100',               // İptal
    self::merkez => 'badge badge-secondary text-purple-500 bg-purple-100',         // Merkezde
    self::late => 'badge badge-warning text-orange-500 bg-orange-100',             // Gecikti
    self::forwarded => 'badge badge-info text-indigo-500 bg-indigo-100',           // Yönlendirildi
    self::finish => 'badge badge-success text-teal-500 bg-teal-100',               // Bitti
];
        [
    self::waiting => 'badge badge-warning text-white bg-yellow-600',            // Bekliyor
    self::awaiting_approve => 'badge badge-info text-white bg-blue-600',        // Onay Bekliyor
    self::confirmed => 'badge badge-success text-white bg-green-600',           // Onaylı
    self::rejected => 'badge badge-error text-white bg-red-600',                // Onaylanmadı
    self::cancel => 'badge badge-neutral text-white bg-gray-600',               // İptal
    self::merkez => 'badge badge-secondary text-white bg-purple-600',           // Merkezde
    self::late => 'badge badge-warning text-white bg-orange-600',               // Gecikti
    self::forwarded => 'badge badge-info text-white bg-indigo-600',             // Yönlendirildi
    self::finish => 'badge badge-success text-white bg-teal-600',               // Bitti
];
         */
    }
}
