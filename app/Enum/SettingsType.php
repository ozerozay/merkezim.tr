<?php

namespace App\Enum;

enum SettingsType: string
{
    case site_name = 'site_name';
    case shop_active = 'shop_active';
    case website_active = 'website_active';

    case client_page_seans = 'client_page_seans';
    case client_page_seans_show_zero = 'client_page_seans_show_zero';
    case client_page_seans_show_category = 'client_page_seans_show_category';
    case client_page_seans_add_seans = 'client_page_seans_add_seans';

    case client_page_appointment = 'client_page_appointment';
    case client_page_appointment_show_services = 'client_page_appointment_show_services';
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

}
