<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'menu_seans' => 'Hizmetlerim',
    'page_seans_add_seans' => 'Seans Yükle',
    'page_seans_category' => 'Kategori',
    'page_seans_remaining' => 'Kalan',
    'page_seans_total' => 'Toplam',

    'menu_appointment' => 'Randevularım',
    'page_appointment_subtitle' => 'Yeni bir randevu oluşturmak için adımları takip edin',
    'page_appointment_create' => 'Randevu Oluştur',
    'page_appointment_review_tip' => 'DEĞERLENDİR',

    'waiting' => 'Bekliyor',
    'awaiting_approve' => 'Onay Bekliyor',
    'confirmed' => 'Onaylı',
    'rejected' => 'Onaylanmadı',
    'cancel' => 'İptal',
    'merkez' => 'Merkezde',
    'late' => 'Gecikti',
    'forwarded' => 'Yönlendirildi',
    'finish' => 'Bitti',
    'teyitli' => 'Teyitli',

    'menu_payments' => 'Taksitlerim',
    'page_taksit_subtitle' => 'İşlem yapmak takside kupona dokunun.',
    'page_taksit_pay' => 'ÖDEME YAP',

    'menu_offer' => 'Tekliflerim',
    'page_offer_subtitle' => 'İşlem yapmak istediğiniz teklife dokunun.',

    'menu_coupon' => 'Kuponlarım',

    'menu_referans' => 'Davet Et',
    'page_referans_subtitle' => 'Sevdiklerinizi davet edin, hediyeler kazanın.',

    'menu_earn' => 'Kullan - Kazan',
    'menu_invoice' => 'Faturalarım',
    'menu_profil' => 'Profil',
    'menu_logout' => 'Çıkış',

    'menu_shop' => 'ONLİNE MAĞAZA',
    'page_shop_package_subtitle' => 'Online alın hemen, randevunuzu hemen oluşturun.',
    'page_shop_package_filter' => 'Filtrele',

    'search' => 'Ara',

    'loading_overlay_message' => 'Lütfen bekleyin...',
    'page_appointment_create' => 'Randevu Oluştur',
    'page_appointment_subtitle' => 'Yeni bir randevu oluşturmak için adımları takip edin',
    'appointment_step_titles' => ['Randevu', 'Şube', 'Kategori', 'Hizmet', 'Tarih', 'Tamam'],
    'appointment_type_date' => [
        'title' => 'Tarih ve Saat Seçerek',
        'description' => 'Belirli bir tarih ve saat seçerek randevunuzu oluşturabilirsiniz.',
        'example' => 'Örnek: :date',
    ],
    'appointment_type_range' => [
        'title' => 'Tarih Aralığı Girerek',
        'description' => 'Bir tarih aralığı belirleyerek randevunuz için zaman dilimi oluşturabilirsiniz.',
        'example' => 'Örnek: :start_date - :end_date tarihleri arasındaki boşlukları görün',
    ],
    'appointment_type_multi' => [
        'title' => 'Birden Fazla Tarih Seçerek',
        'description' => 'Birden fazla tarih seçerek randevularınızı oluşturabilirsiniz.',
        'example' => 'Örnek: :days günlerindeki boşlukları görün',
    ],
    'branch_selection_title' => 'Şube Seçimi',
    'branch_selection_description' => 'Randevu almak istediğiniz şubeyi seçin',
    'service_category_selection_title' => 'Kategori Seçimi',
    'service_category_selection_description' => 'Bu kategorideki hizmetleri görüntülemek için tıklayın',
    'room_selection_title' => 'Oda Seçimi',
    'service_selection_title' => 'Hizmet Seçimi',
    'service_remaining' => ':remaining adet kaldı',
    'appointment_date_selection_title' => 'Tarih Seçimi',
    'appointment_notes_label' => 'Randevu Notları',
    'appointment_create_button' => 'Randevu Oluştur',
    'appointment_range_find_button' => 'Uygun Saatleri Bul',
    'appointment_complete_title' => 'Randevu Oluşturuldu',
    'appointment_complete_message' => 'Randevunuz başarıyla oluşturuldu.',
    'navigation_back' => 'Geri',
    'navigation_next' => 'İleri',

    // Seans Sayfası
    'page_seans' => [
        'title' => 'Hizmetlerim',
        'subtitle' => 'Seans bilgilerinizi görüntüleyin ve yönetin',
        'stats' => [
            'total_services' => 'Toplam Hizmet',
            'ongoing_services' => 'Devam Eden',
            'completed_services' => 'Tamamlanan'
        ],
        'sections' => [
            'ongoing' => [
                'title' => 'Devam Eden Hizmetler',
                'count' => ':count hizmet'
            ],
            'completed' => [
                'title' => 'Tamamlanan Hizmetler',
                'count' => ':count hizmet'
            ]
        ],
        'service' => [
            'sessions' => ':count seans',
            'category' => 'Kategori'
        ],
        'loading' => 'Yükleniyor...'
    ],

    'page_appointment' => [
        'subtitle' => 'Randevularınızı görüntüleyebilir ve yönetebilirsiniz.',
        'create' => 'Yeni Randevu',
        'stats' => [
            'total' => 'Toplam Randevu',
            'pending' => 'Bekleyen',
            'completed' => 'Tamamlanan',
        ],
        // ... diğer çeviriler
    ],

    'loading' => 'Yükleniyor...',

    'appointment' => [
        'create' => [
            'title' => 'Randevu Al',
            'subtitle' => 'Randevu türünü seçerek başlayın',
        ],
        'steps' => [
            '1' => 'Tür',
            '2' => 'Kategori',
            '3' => 'Hizmet',
            '4' => 'Tarih',
        ],
        'type' => [
            'title' => 'Randevu Türü',
            'range' => [
                'title' => 'Aralık',
                'description' => 'Tarih aralığı seçin',
            ],
            'date' => [
                'title' => 'Tek Seans',
                'description' => 'Tek seans randevu',
            ],
            'multi' => [
                'title' => 'Çoklu',
                'description' => 'Çoklu seans randevu',
            ],
        ],
        'category' => [
            'title' => 'Kategori Seçin',
            'subtitle' => 'Randevu almak istediğiniz kategoriyi seçin',
        ],
        'service' => [
            'title' => 'Hizmet Seçin',
            'subtitle' => 'Almak istediğiniz hizmetleri seçin',
            'remaining' => 'Kalan: :count',
            'duration' => 'dakika',
            'select_room' => 'Oda seçin',
            'continue' => 'Devam',
            'room' => 'Oda',
        ],
        'date' => [
            'title' => 'Tarih Seçin',
            'subtitle' => 'Size uygun tarihi seçin',
            'select' => 'Tarih seçin',
            'message' => 'Not ekleyin',
            'create' => 'Randevu Oluştur',
        ],
        'success' => 'Randevunuz başarıyla oluşturuldu',
        'errors' => [
            'initialization' => 'Randevu oluşturma başlatılamadı',
            'service' => 'Lütfen en az bir hizmet seçin',
            'date' => 'Lütfen tarih seçin',
            'room' => 'Lütfen oda seçin',
        ],
        'back' => 'Geri',
        'summary' => [
            'type' => 'Randevu Tipi',
            'category' => 'Kategori',
            'services' => 'Seçilen Hizmetler',
            'total_duration' => 'Toplam Süre',
            'room' => 'Oda'
        ],
    ],

    // Adımlar
    'step_type_title' => 'Randevu Türü Seçimi',
    'step_branch_title' => 'Şube Seçimi',
    'step_category_title' => 'Kategori Seçimi',
    'step_service_title' => 'Hizmet Seçimi',
    'step_date_title' => 'Tarih Seçimi',
    'step_complete_title' => 'Tamamlandı',

    // Randevu Türleri
    'type_date' => 'Tek Randevu',
    'type_range' => 'Tarih Aralığı',
    'type_multi' => 'Çoklu Tarih',
    'type_description' => 'Randevu türünü seçin',

    // Seçim Başlıkları
    'branch_selection_title' => 'Şube Seçimi',
    'branch_selection_description' => 'Randevu almak istediğiniz şubeyi seçin',
    'service_category_selection_title' => 'Kategori Seçimi',
    'service_category_selection_description' => 'Bu kategorideki hizmetleri görüntülemek için tıklayın',
    'service_selection_title' => 'Hizmet Seçimi',
    'room_selection_title' => 'Oda Seçimi',
    'appointment_date_selection_title' => 'Tarih Seçimi',

    // Servis Bilgileri
    'service_remaining' => ':remaining adet kaldı',
    'service_duration' => ':duration dk',

    // Form Alanları
    'appointment_notes_label' => 'Randevu Notları',
    'appointment_date_label' => 'Randevu Tarihi',

    // Butonlar
    'navigation_next' => 'İleri',
    'navigation_back' => 'Geri',
    'appointment_create_button' => 'Randevu Oluştur',
    'appointment_range_find_button' => 'Uygun Saatleri Bul',

    // Başarı/Hata Mesajları
    'appointment_complete_title' => 'Randevu Oluşturuldu',
    'appointment_complete_message' => 'Randevunuz başarıyla oluşturuldu.',
    'error_service_required' => 'En az bir hizmet seçmelisiniz.',
    'error_room_required' => 'Oda seçmelisiniz.',
    'error_date_required' => 'Tarih seçimi zorunludur.',
    'error_duration' => 'Süre hesaplanamadı.',
    'error_try_again' => 'Bir hata oluştu, lütfen tekrar deneyin',
    'success_appointment_pending' => 'Randevu talebiniz onaylandığında bildirim alacaksınız.',
    'success_appointment_created' => 'Randevunuz oluşturuldu.',

    // Uygun Saatler
    'appointment_available_slots_title' => 'Uygun Saatler',
    'error_no_available_slots' => 'Seçilen tarih aralığında uygun randevu saati bulunamadı',

    // Özet Bölümü
    'appointment_summary_title' => 'Randevu Özeti',
    'appointment_summary_category' => 'Kategori',
    'appointment_summary_services' => 'Seçilen Hizmetler',
    'appointment_summary_total_duration' => 'Toplam Süre',
    'appointment_summary_date' => 'Tarih',

    // Genel Hatalar
    'error_no_services_found' => 'Bu kategoride aktif hizmetiniz bulunmamaktadır',

    'service_duration_minutes' => 'dakika',
    'service_remaining' => ':remaining adet kaldı',

    'appointment_notes_placeholder' => 'Randevunuz ile ilgili eklemek istediğiniz notları buraya yazabilirsiniz...',

    'no_active_packages_title' => 'Aktif Paketiniz Bulunmuyor',
    'no_active_packages_description' => 'Randevu oluşturmak için bir paket satın alabilir veya rezervasyon talebi oluşturabilirsiniz.',
    'view_packages_button' => 'Paketleri İncele',
    'create_reservation_request_button' => 'Rezervasyon Talebi Oluştur',

    'no_active_services_title' => 'Aktif Hizmetiniz Bulunmuyor',
    'no_active_services_description' => 'Randevu oluşturmak için bir paket satın alabilir veya rezervasyon talebi oluşturabilirsiniz.',
    'view_packages_button' => 'Paketleri İncele',
    'create_reservation_request_button' => 'Rezervasyon Talebi Oluştur',

    'date_and_time_preferences' => 'Tarih ve Zaman Tercihleri',
    'additional_notes' => 'Ek Notlar',
    'reservation_summary' => 'Rezervasyon Özeti',
    'selected_services' => 'Seçilen Hizmetler',
    'minutes_short' => 'dk',

    'select_services_placeholder' => 'Hizmet seçiniz...',
    'search_services' => 'Hizmet ara...',
    'no_services_found' => 'Hizmet bulunamadı',

    'error_service_required' => 'En az bir hizmet seçmelisiniz.',
    'error_date_required' => 'Tarih seçimi zorunludur.',
    'error_date_must_be_future' => 'Geçmiş bir tarih seçemezsiniz.',
    'error_time_required' => 'Tercih ettiğiniz zaman dilimini seçmelisiniz.',
    'reservation_request_success' => 'Rezervasyon talebiniz başarıyla oluşturuldu.',
    'reservation_request_error' => 'Rezervasyon talebi oluşturulurken bir hata oluştu.',

    // Sayfa başlıkları
    'reservation_request_title' => 'Rezervasyon Talebi',
    'reservation_request_subtitle' => 'Tercih ettiğiniz hizmetleri ve zamanı seçin',

    // Form alanları
    'select_services' => 'Hizmet Seçimi',
    'select_services_placeholder' => 'Hizmet seçiniz...',
    'search_services' => 'Hizmet ara...',
    'no_services_found' => 'Hizmet bulunamadı',
    'date_and_time_preferences' => 'Tarih ve Zaman Tercihleri',
    'preferred_date' => 'Tercih Ettiğiniz Tarih',
    'preferred_time' => 'Tercih Ettiğiniz Zaman Dilimi',
    'additional_notes' => 'Ek Notlar',
    'reservation_note' => 'Notunuz',
    'reservation_note_placeholder' => 'Rezervasyonunuz ile ilgili eklemek istediğiniz notları buraya yazabilirsiniz...',

    // Özet bölümü
    'reservation_summary' => 'Rezervasyon Özeti',
    'selected_services' => 'Seçilen Hizmetler',
    'service_duration_minutes' => 'dakika',
    'minutes_short' => 'dk',

    // Butonlar
    'submit_reservation_request' => 'Rezervasyon Talebi Oluştur',
    'view_packages_button' => 'Paketleri İncele',
    'create_reservation_request_button' => 'Rezervasyon Talebi Oluştur',

    'additional_info' => 'İletişim Bilgileri',
    'phone_number' => 'Telefon Numarası',
    'phone_placeholder' => '5XX XXX XX XX',
    'error_phone_required' => 'Telefon numarası zorunludur',
    'error_phone_invalid' => 'Geçerli bir telefon numarası giriniz',

    'select_branch' => 'Şube Seçimi',
    'error_branch_required' => 'Lütfen bir şube seçin',

];
