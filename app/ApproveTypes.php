<?php

namespace App;

enum ApproveTypes: string
{
    case client_add_service = 'client_add_service';
    case client_use_service = 'client_use_service';
    case client_transfer_service = 'client_transfer_service';
    case client_update_label = 'client_update_label';
    case create_adisyon = 'create_adisyon';
    case create_product_sale = 'create_product_sale';
    case create_coupon = 'create_coupon';
    case create_offer = 'create_offer';
    case update_offer = 'update_offer';
    case cancel_offer = 'cancel_offer';
    case approve_offer = 'approve_offer';
    case create_taksit = 'create_taksit';
    case mahsup = 'mahsup';
    case payment = 'payment';
    case manuel_appointment = 'manuel_appointment';
    case close_appointment = 'close_appointment';

    case create_tahsilat = 'create_tahsilat';

    public function label(): string
    {
        return match ($this) {
            self::client_add_service => 'Hizmet Ekleme',
            self::client_use_service => 'Hizmet Kullanımı',
            self::client_transfer_service => 'Hizmet Transferi',
            self::client_update_label => 'Etiket Güncelleme',
            self::create_adisyon => 'Adisyon Oluşturma',
            self::create_product_sale => 'Ürün Satışı Oluşturma',
            self::create_coupon => 'Kupon Oluşturma',
            self::create_offer => 'Teklif Oluşturma',
            self::update_offer => 'Teklif Güncelleme',
            self::cancel_offer => 'Teklif İptali',
            self::approve_offer => 'Teklif Onayı',
            self::create_taksit => 'Taksit Oluşturma',
            self::mahsup => 'Mahsup',
            self::payment => 'Ödeme',
            self::manuel_appointment => 'Manuel Randevu',
            self::close_appointment => 'Randevu Kapatma',
            self::create_tahsilat => 'Tahsilat Oluşturma',
        };
    }
}
