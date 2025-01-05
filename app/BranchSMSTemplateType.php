<?php

namespace App;

use App\Models\SmsTemplateBranch;

enum BranchSMSTemplateType: string
{
    // Randevu ile ilgili şablonlar
    case appointment_one_day_before = 'appointment_one_day_before';
    case appointment_on_day = 'appointment_on_day';
    case appointment_two_days_before = 'appointment_two_days_before';
    case appointment_five_days_before = 'appointment_five_days_before';
    case appointment_completed = 'appointment_completed';

    // Teklif ile ilgili şablonlar
    case offer_five_days_before = 'offer_five_days_before';
    case offer_two_days_before = 'offer_two_days_before';
    case offer_due_today = 'offer_due_today';

    // Ödeme ile ilgili şablonlar
    case payment_five_days_before = 'payment_five_days_before';
    case payment_two_days_before = 'payment_two_days_before';
    case payment_due_today = 'payment_due_today';
    case payment_two_days_after = 'payment_two_days_after';
    case payment_five_days_after = 'payment_five_days_after';
    case payment_seven_days_after = 'payment_seven_days_after';
    case payment_fifteen_days_after = 'payment_fifteen_days_after';
    case payment_twenty_days_after = 'payment_twenty_days_after';

    // Hoşgeldiniz mesajı
    case welcome_message = 'welcome_message';

    /**
     * Varsayılan mesaj şablonlarını döner.
     */
    public function label(): string
    {
        return match ($this) {
            // Randevu şablonları
            self::appointment_one_day_before => "Sayın {{ müşteri adı }}, yarın saat {{ randevu saati }}'nde randevunuz bulunmaktadır.",
            self::appointment_on_day => "Sayın {{ müşteri adı }}, bugün saat {{ randevu saati }}'nde randevunuz bulunmaktadır.",
            self::appointment_two_days_before => "Sayın {{ müşteri adı }}, iki gün sonra saat {{ randevu saati }}'nde randevunuz bulunmaktadır.",
            self::appointment_five_days_before => "Sayın {{ müşteri adı }}, beş gün sonra saat {{ randevu saati }}'nde randevunuz bulunmaktadır.",
            self::appointment_completed => 'Sayın {{ müşteri adı }}, randevunuz tamamlanmıştır. Randevunuzu değerlendirmek için {{ site_link }} adresini ziyaret edebilirsiniz.',

            // Teklif şablonları
            self::offer_five_days_before => 'Sayın {{ müşteri adı }}, teklifinizin süresi 5 gün sonra doluyor. Detaylı inceleme için {{ site_link }} adresini ziyaret edebilirsiniz.',
            self::offer_two_days_before => 'Sayın {{ müşteri adı }}, teklifinizin süresi 2 gün sonra doluyor. Detaylı inceleme için {{ site_link }} adresini ziyaret edebilirsiniz.',
            self::offer_due_today => 'Sayın {{ müşteri adı }}, teklifinizin süresi bugün doluyor. Kaçırmamak için hemen değerlendirin: {{ site_link }}.',

            // Ödeme şablonları
            self::payment_five_days_before => 'Sayın {{ müşteri adı }}, {{ ödeme tarihi }} tarihindeki ödemenize 5 gün kalmıştır. Ödeme tutarı: {{ tutar }}.',
            self::payment_two_days_before => 'Sayın {{ müşteri adı }}, {{ ödeme tarihi }} tarihindeki ödemenize 2 gün kalmıştır. Ödeme tutarı: {{ tutar }}.',
            self::payment_due_today => 'Sayın {{ müşteri adı }}, {{ ödeme tarihi }} tarihindeki ödemeniz bugün vadesindedir. Ödeme tutarı: {{ tutar }}.',
            self::payment_two_days_after => 'Sayın {{ müşteri adı }}, {{ ödeme tarihi }} tarihindeki ödemenizin üzerinden 2 gün geçmiştir. Gecikmiş ödeme tutarı: {{ tutar }}.',
            self::payment_five_days_after => 'Sayın {{ müşteri adı }}, {{ ödeme tarihi }} tarihindeki ödemenizin üzerinden 5 gün geçmiştir. Gecikmiş ödeme tutarı: {{ tutar }}.',
            self::payment_seven_days_after => 'Sayın {{ müşteri adı }}, {{ ödeme tarihi }} tarihindeki ödemenizin üzerinden 7 gün geçmiştir. Gecikmiş ödeme tutarı: {{ tutar }}.',
            self::payment_fifteen_days_after => 'Sayın {{ müşteri adı }}, {{ ödeme tarihi }} tarihindeki ödemenizin üzerinden 15 gün geçmiştir. Gecikmiş ödeme tutarı: {{ tutar }}.',
            self::payment_twenty_days_after => 'Sayın {{ müşteri adı }}, {{ ödeme tarihi }} tarihindeki ödemenizin üzerinden 20 gün geçmiştir. Gecikmiş ödeme tutarı: {{ tutar }}.',

            // Hoşgeldiniz mesajı
            self::welcome_message => 'Sayın {{ müşteri adı }}, aramıza hoş geldiniz! Tüm işlemleriniz için {{ site_link }} adresini ziyaret edebilirsiniz.',
        };
    }

    /**
     * Türkçe isim döner.
     */
    public function turkishName(): string
    {
        return match ($this) {
            // Randevu isimleri
            self::appointment_one_day_before => 'Randevudan 1 Gün Önce',
            self::appointment_on_day => 'Randevu Günü',
            self::appointment_two_days_before => 'Randevudan 2 Gün Önce',
            self::appointment_five_days_before => 'Randevudan 5 Gün Önce',
            self::appointment_completed => 'Randevu Tamamlandı',

            // Teklif isimleri
            self::offer_five_days_before => 'Teklifin Bitmesine 5 Gün Kala',
            self::offer_two_days_before => 'Teklifin Bitmesine 2 Gün Kala',
            self::offer_due_today => 'Teklif Günü',

            // Ödeme isimleri
            self::payment_five_days_before => 'Ödemeye 5 Gün Kala',
            self::payment_two_days_before => 'Ödemeye 2 Gün Kala',
            self::payment_due_today => 'Ödeme Günü',
            self::payment_two_days_after => 'Ödemeden 2 Gün Sonra',
            self::payment_five_days_after => 'Ödemeden 5 Gün Sonra',
            self::payment_seven_days_after => 'Ödemeden 7 Gün Sonra',
            self::payment_fifteen_days_after => 'Ödemeden 15 Gün Sonra',
            self::payment_twenty_days_after => 'Ödemeden 20 Gün Sonra',

            // Hoşgeldiniz
            self::welcome_message => 'Hoş Geldiniz Mesajı',
        };
    }

    public function prepareMessage(SmsTemplateBranch $template, array $parameters = []): string
    {
        $content = $template->content ?? $this->label();

        foreach ($parameters as $key => $value) {
            $content = str_replace("{{ $key }}", $value, $content);
        }

        return preg_replace('/{{\s*[\w]+\s*}}/', '', $content); // Hatalı placeholderları temizler
    }
}
