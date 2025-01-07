<?php

namespace App\Enum;

use App\AppointmentStatus;

enum SettingsType: string
{
    case site_name = 'site_name';
    case shop_active = 'shop_active';
    case website_active = 'website_active';

    case client_page_seans = 'client_page_seans';
    case client_page_seans_show_zero = 'client_page_seans_show_zero';
    case client_page_seans_show_category = 'client_page_seans_show_category';
    case client_page_seans_add_seans = 'client_page_seans_add_seans';
    case client_page_seans_show_stats = 'client_page_seans_show_stats';

    case client_page_appointment = 'client_page_appointment';
    case client_page_appointment_show_services = 'client_page_appointment_show_services';
    case client_page_appointment_show_stats = 'client_page_appointment_show_stats';
    case client_page_appointment_create = 'client_page_appointment_create';
    case client_page_appointment_show = 'client_page_appointment_show';
    case client_page_appointment_create_once_category = 'client_page_appointment_create_once_category';
    case client_page_appointment_create_branches = 'client_page_appointment_create_branches';
    case client_page_appointment_create_appointment_approve = 'client_page_appointment_create_appointment_approve';
    case client_page_appointment_create_appointment_late_payment = 'client_page_appointment_create_appointment_late_payment';
    case client_page_appointment_cancel_time = 'client_page_appointment_cancel_time';

    case client_page_taksit = 'client_page_taksit';
    case client_page_taksit_pay = 'client_page_taksit_pay';
    case client_page_taksit_show_zero = 'client_page_taksit_show_zero';
    case client_page_taksit_show_locked = 'client_page_taksit_show_locked';

    case client_page_offer = 'client_page_offer';
    case client_page_offer_request = 'client_page_offer_request';

    case client_page_coupon = 'client_page_coupon';
    case client_page_referans = 'client_page_referans';
    case client_page_package = 'client_page_package';
    case client_page_earn = 'client_page_earn';
    case client_page_fatura = 'client_page_fatura';
    case client_page_support = 'client_page_support';

    case client_page_shop_include_kdv = 'client_page_shop_include_kdv';

    case client_payment_types = 'client_payment_types';

    case payment_taksit_include_kdv = 'payment_taksit_include_kdv';
    case payment_taksit_include_komisyon = 'payment_taksit_include_komisyon';

    case payment_tip_include_kdv = 'payment_tip_include_kdv';
    case payment_tip_include_komisyon = 'payment_tip_include_komisyon';

    case payment_offer_include_kdv = 'payment_offer_include_kdv';
    case payment_offer_include_komisyon = 'payment_offer_include_komisyon';

    /**
     * Her ayar için varsayılan değerleri döndürür
     */
    public static function getDefaultValue(string $setting): mixed
    {
        return match ($setting) {
            // Site Ayarları
            self::site_name->name => 'Site İsmi',
            self::shop_active->name => true,
            self::website_active->name => true,

            // Seans Sayfası Ayarları
            self::client_page_seans->name => true,
            self::client_page_seans_show_zero->name => true,
            self::client_page_seans_show_category->name => true,
            self::client_page_seans_add_seans->name => true,
            self::client_page_seans_show_stats->name => true,

            // Randevu Sayfası Ayarları
            self::client_page_appointment->name => true,
            self::client_page_appointment_show_services->name => true,
            self::client_page_appointment_show_stats->name => true,
            self::client_page_appointment_create->name => ['manuel', 'range', 'multi'],
            self::client_page_appointment_show->name => AppointmentStatus::cases(),
            self::client_page_appointment_create_once_category->name => true,
            self::client_page_appointment_create_branches->name => [1, 2],
            self::client_page_appointment_create_appointment_approve->name => true,
            self::client_page_appointment_create_appointment_late_payment->name => true,
            self::client_page_appointment_cancel_time->name => 0,

            // Taksit Sayfası Ayarları
            self::client_page_taksit->name => true,
            self::client_page_taksit_pay->name => true,
            self::client_page_taksit_show_locked->name => true,
            self::client_page_taksit_show_zero->name => true,

            // Teklif Sayfası Ayarları
            self::client_page_offer->name => true,
            self::client_page_offer_request->name => true,

            // Diğer Sayfa Ayarları
            self::client_page_coupon->name => true,
            self::client_page_referans->name => true,
            self::client_page_package->name => true,
            self::client_page_earn->name => true,
            self::client_page_fatura->name => true,
            self::client_page_support->name => true,

            // Mağaza Ayarları
            self::client_page_shop_include_kdv->name => 0,
            self::client_payment_types->name => ['havale', 'kk'],

            // Ödeme Ayarları
            self::payment_taksit_include_kdv->name => 0,
            self::payment_taksit_include_komisyon->name => 0,
            self::payment_tip_include_kdv->name => 0,
            self::payment_tip_include_komisyon->name => 0,
            self::payment_offer_include_kdv->name => 0,
            self::payment_offer_include_komisyon->name => 0,

            default => null,
        };
    }
}
