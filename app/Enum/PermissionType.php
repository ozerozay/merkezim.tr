<?php

namespace App\Enum;

enum PermissionType: string
{
    case change_sale_price = 'change_sale_price';

    case action_client_create = 'action_client_create';
    case action_client_add_note = 'action_client_add_note';
    case action_client_add_label = 'action_client_add_label';
    case action_client_create_service = 'action_client_create_service';
    case action_client_use_service = 'action_client_use_service';
    case action_client_create_offer = 'action_client_create_offer';
    case action_client_create_appointment = 'action_client_create_appointment';
    case action_adisyon_create = 'action_adisyon_create';
    case action_create_coupon = 'action_create_coupon';
    case action_client_create_taksit = 'action_client_create_taksit';
    case action_client_product_sale = 'action_client_product_sale';
    case action_client_sale = 'action_client_sale';

    case client_profil_note = 'client_profil_note';
    case client_profil_offer = 'client_profil_offer';
    case client_profil_service = 'client_profil_service';
    case client_profil_taksit = 'client_profil_taksit';
    case client_profil_sale = 'client_profil_sale';
    case client_profil_product = 'client_profil_product';
    case client_profil_appointment = 'client_profil_appointment';

    case page_kasa = 'page_kasa';
    case page_agenda = 'page_agenda';
    case page_talep = 'page_talep';
    case page_reports = 'page_reports';
    case page_statistics = 'page_statistics';
    case page_randevu = 'page_randevu';
    case page_approve = 'page_approve';

    case admin_settings = 'admin_settings';
    case admin_definations = 'admin_definations';

    case kasa_mahsup = 'kasa_mahsup';
    case kasa_make_payment = 'kasa_make_payment';
}
