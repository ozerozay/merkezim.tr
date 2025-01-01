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
    case action_edit_user = 'action_edit_user';
    case action_send_sms = 'action_send_sms';

    case client_profil = 'client_profil';
    case client_profil_note = 'client_profil_note';
    case client_profil_offer = 'client_profil_offer';
    case client_profil_service = 'client_profil_service';
    case client_profil_taksit = 'client_profil_taksit';
    case client_profil_sale = 'client_profil_sale';
    case client_profil_product = 'client_profil_product';
    case client_profil_appointment = 'client_profil_appointment';
    case client_profil_adisyon = 'client_profil_adisyon';
    case client_profil_coupon = 'client_profil_coupon';

    case page_kasa = 'page_kasa';
    case page_agenda = 'page_agenda';
    case page_talep = 'page_talep';
    case page_reports = 'page_reports';
    case page_statistics = 'page_statistics';
    case page_randevu = 'page_randevu';
    case page_approve = 'page_approve';
    case page_finger = 'page_finger';

    case admin_settings = 'admin_settings';
    case admin_definations = 'admin_definations';

    case kasa_mahsup = 'kasa_mahsup';
    case kasa_make_payment = 'kasa_make_payment';
    case action_client_tahsilat = 'action_client_tahsilat';

    case website_settings = 'website_settings';

    case note_process = 'note_process';
    case coupon_process = 'coupon_process';
    case offer_process = 'offer_process';
    case service_process = 'service_process';
    case taksit_process = 'taksit_process';
    case sale_process = 'sale_process';
    case sale_product_process = 'sale_product_process';
    case kasa_detail_process = 'kasa_detail_process';
    case appointment_process = 'appointment_process';
    case adisyon_process = 'adisyon_process';
    case talep_process = 'process_process';

    public function label(): string
    {
        return match ($this) {
            self::change_sale_price => 'Satış fiyatını değiştirme yetkisi',

            self::action_client_create => 'Yeni müşteri oluşturma yetkisi',
            self::action_client_add_note => 'Müşteri notu ekleme yetkisi',
            self::action_client_add_label => 'Müşteri etiketi ekleme yetkisi',
            self::action_client_create_service => 'Müşteri hizmeti oluşturma yetkisi',
            self::action_client_use_service => 'Müşteri hizmetini kullanma yetkisi',
            self::action_client_create_offer => 'Teklif oluşturma yetkisi',
            self::action_client_create_appointment => 'Randevu oluşturma yetkisi',
            self::action_adisyon_create => 'Adisyon oluşturma yetkisi',
            self::action_create_coupon => 'Kupon oluşturma yetkisi',
            self::action_client_create_taksit => 'Taksit oluşturma yetkisi',
            self::action_client_product_sale => 'Ürün satışı yetkisi',
            self::action_client_sale => 'Satış işlemi yetkisi',
            self::action_edit_user => 'Kullanıcı düzenleme yetkisi',
            self::action_send_sms => 'SMS gönderme yetkisi',

            self::client_profil => 'Müşteri profil görüntüleme yetkisi',
            self::client_profil_note => 'Müşteri profil notları görüntüleme yetkisi',
            self::client_profil_offer => 'Müşteri tekliflerini görüntüleme yetkisi',
            self::client_profil_service => 'Müşteri hizmetlerini görüntüleme yetkisi',
            self::client_profil_taksit => 'Müşteri taksitlerini görüntüleme yetkisi',
            self::client_profil_sale => 'Müşteri satışlarını görüntüleme yetkisi',
            self::client_profil_product => 'Müşteri ürünlerini görüntüleme yetkisi',
            self::client_profil_appointment => 'Müşteri randevularını görüntüleme yetkisi',
            self::client_profil_adisyon => 'Müşteri adisyonlarını görüntüleme yetkisi',
            self::client_profil_coupon => 'Müşteri kuponlarını görüntüleme yetkisi',

            self::page_kasa => 'Kasa sayfasına erişim yetkisi',
            self::page_agenda => 'Ajanda sayfasına erişim yetkisi',
            self::page_talep => 'Talep sayfasına erişim yetkisi',
            self::page_reports => 'Raporlar sayfasına erişim yetkisi',
            self::page_statistics => 'İstatistikler sayfasına erişim yetkisi',
            self::page_randevu => 'Randevu sayfasına erişim yetkisi',
            self::page_approve => 'Onay sayfasına erişim yetkisi',
            self::page_finger => 'Parmak izi sayfasına erişim yetkisi',

            self::admin_settings => 'Yönetim ayarları yetkisi',
            self::admin_definations => 'Yönetim tanımları yetkisi',

            self::kasa_mahsup => 'Kasa mahsup işlemleri yetkisi',
            self::kasa_make_payment => 'Ödeme işlemi yapma yetkisi',
            self::action_client_tahsilat => 'Tahsilat işlemleri yetkisi',

            self::website_settings => 'Web sitesi ayarları yetkisi',

            self::note_process => 'Not işlemleri yetkisi',
            self::coupon_process => 'Kupon işlemleri',
            self::offer_process => 'Teklif işlemleri',
            self::service_process => 'Hizmet işlemleri',
            self::taksit_process => 'Taksit işlemleri',
            self::sale_process => 'Satış işlemleri',
            self::sale_product_process => 'Ürün satış işlemleri',
            self::kasa_detail_process => 'Kasa detay işlemleri',
            self::appointment_process => 'Randevu işlemleri',
            self::adisyon_process => 'Adisyon işlemleri',
            self::talep_process => 'Talep işlemleri',
        };
    }
}
