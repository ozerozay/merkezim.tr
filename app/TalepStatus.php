<?php

namespace App;

enum TalepStatus: string
{
    case bekleniyor = 'bekleniyor';
    case cevapsiz = 'cevapsiz';
    case kendisi = 'kendisi';
    case mesgul = 'mesgul';
    case sonra = 'sonra';
    case ilgilenmiyor = 'ilgilenmiyor';
    case uzak = 'uzak';
    case pahali = 'pahali';
    case yanlis = 'yanlis';
    case iptal = 'iptal';
    case randevu = 'randevu';
    case olmadi = 'olmadi';

    public static function has(string $value): bool
    {
        return collect(self::cases())->contains(fn ($case) => $case->value === $value);
    }

    public function label(): string
    {
        return match ($this) {
            self::bekleniyor => 'Bekleniyor',
            self::cevapsiz => 'Cevapsız',
            self::kendisi => 'Kendisi Arayacak',
            self::mesgul => 'Meşgul',
            self::sonra => 'Sonra Ara',
            self::ilgilenmiyor => 'İlgilenmiyor',
            self::uzak => 'Uzak',
            self::pahali => 'Pahalı',
            self::yanlis => 'Yanlış',
            self::iptal => 'İptal',
            self::randevu => 'Randevu',
            self::olmadi => 'Satış Olmadı',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::bekleniyor => 'warning',
            self::cevapsiz => 'error',
            self::kendisi => 'secondary',
            self::mesgul => 'warning',
            self::sonra => 'success',
            self::ilgilenmiyor => 'error',
            self::uzak => 'primary',
            self::pahali => 'secondary',
            self::yanlis => 'error',
            self::iptal => 'error',
            self::randevu => 'success',
            self::olmadi => 'error',
        };
    }
}
