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
}
