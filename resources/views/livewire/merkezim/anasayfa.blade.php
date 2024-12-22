<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.merkezim')]
class extends Component {
};
?>
<div>

    <div
        class="hero"
        style="background-image: url(https://img.daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.webp);">
        <div class="hero-overlay bg-opacity-90"></div>
        <div class="hero-content text-neutral-content text-center">
            <div class="max-w-md">
                <h1 class="mb-5 text-5xl font-bold  text-white">7/24 SATIŞ YAPIN</h1>
                <p class="mb-5  text-white">
                    Online işlem merkezi ile işletme yükünüzü minimuma indirin.
                </p>
                <button class="btn btn-primary">HADİ BAŞLAYALIM</button>
            </div>
        </div>
    </div>
    <section class="relative bg-primary h-screen flex items-center justify-center overflow-hidden">
        <!-- Arka Plan Video veya Görsel (Önerilen video efektleri) -->
        <div class="absolute inset-0 w-full h-full bg-cover bg-center"
             style="background-image: url('your-image-path.jpg'); opacity: 0.5;">
        </div>

        <!-- Hero Content -->
        <div class="container mx-auto px-6 relative z-10 text-center text-white">
            <h1 class="text-6xl md:text-7xl font-extrabold tracking-wide mb-6 animate__animated animate__fadeIn">
                Yeni Nesil Teknolojiyi Keşfedin
            </h1>

            <!-- Dalgalar veya Dinamik Animasyon Arka Plan -->
            <div
                class="absolute top-0 left-0 w-full h-1/4 bg-gradient-to-b from-primary to-transparent opacity-30"></div>

            <!-- Ana Açıklama -->
            <p class="text-xl mb-8 animate__animated animate__fadeIn animate__delay-1s text-opacity-80">
                Gelecek iş dünyası için yenilikçi çözümler! Hedeflerinize hızlıca ulaşmak için bugün başlamanızı
                öneriyoruz.
            </p>

            <!-- CTA Butonu -->
            <a href="#start"
               class="btn btn-accent text-xl rounded-full px-12 py-4 animate__animated animate__fadeIn animate__delay-2s">
                Hemen Başla
            </a>
        </div>

        <!-- Arka Planda Hareketli Grafikler veya Animasyonlar -->
        <svg class="absolute inset-0 w-full h-full opacity-20 z-0" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 1440 320">
            <path fill="white" fill-opacity="0.2" d="M0,128L1440,64L1440,320L0,320Z"></path>
        </svg>
    </section>

    <section class="py-8 bg-base-200">
        <div class="container mx-auto px-4">
            <!-- Satış Section -->
            <div class="my-12" id="sales">
                <h2 class="text-3xl font-extrabold uppercase text-gray-800 border-b-2 border-primary pb-2 mb-6">
                    Satış</h2>
                <p class="text-lg text-gray-500 mb-6">Satış ve sipariş yönetimi hakkında bilgi alın.</p>
                <!-- Sales Cards -->
                <div class="grid md:grid-cols-3 gap-6">
                    <div
                        class="card bg-base-100 hover:shadow-xl transition transform hover:scale-105 duration-300 ease-in-out border-l-4 border-primary">
                        <div class="card-body p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Satış Raporları</h3>
                            <p class="text-sm text-gray-600">Satışlarınıza dair tüm raporlara hızlıca
                                ulaşabilirsiniz.</p>
                        </div>
                    </div>
                    <div
                        class="card bg-base-100 hover:shadow-xl transition transform hover:scale-105 duration-300 ease-in-out border-l-4 border-primary">
                        <div class="card-body p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Ürün Satışı</h3>
                            <p class="text-sm text-gray-600">Tüm ürün satışlarını tek bir yerden takip edin.</p>
                        </div>
                    </div>
                    <div
                        class="card bg-base-100 hover:shadow-xl transition transform hover:scale-105 duration-300 ease-in-out border-l-4 border-primary">
                        <div class="card-body p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Müşteri Takibi</h3>
                            <p class="text-sm text-gray-600">Müşterilerinize ait satış bilgilerini detaylı bir şekilde
                                gözden geçirin.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diğer bölümler aynı şekilde devam eder... -->

        </div>
    </section>

    <section class="py-8 bg-base-200">
        <div class="container mx-auto px-4">
            <!-- Satış Section -->
            <div class="my-12" id="sales">
                <h2 class="text-4xl font-bold text-white bg-gradient-to-r from-primary to-secondary py-3 px-6 rounded-md shadow-md mb-6 border-b-4 border-accent hover:text-accent transition-all duration-300 ease-in-out">
                    Satış
                </h2>
                <p class="text-lg text-gray-500 mb-6">Satış ve sipariş yönetimi hakkında bilgi alın.</p>
                <!-- Sales Cards -->
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">Satış Raporları</h3>
                            <p>Satışlarınıza dair tüm raporlara hızlıca ulaşabilirsiniz.</p>
                        </div>
                    </div>
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">Ürün Satışı</h3>
                            <p>Tüm ürün satışlarını tek bir yerden takip edin.</p>
                        </div>
                    </div>
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">Müşteri Takibi</h3>
                            <p>Müşterilerinize ait satış bilgilerini detaylı bir şekilde gözden geçirin.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Randevu Section -->
            <div class="my-12" id="appointment">
                <h2 class="text-4xl font-bold text-white bg-gradient-to-r from-primary to-secondary py-3 px-6 rounded-md shadow-md mb-6 border-b-4 border-accent hover:text-accent transition-all duration-300 ease-in-out">
                    Randevu
                </h2>
                <p class="text-lg text-gray-500 mb-6">Randevu sisteminizi yönetmek için özellikler.</p>
                <!-- Appointment Cards -->
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">Randevu Yönetimi</h3>
                            <p>Kolayca randevu alabilir ve iptal edebilirsiniz.</p>
                        </div>
                    </div>
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">Randevu Hatırlatıcıları</h3>
                            <p>Randevularınız için hatırlatmalar alabilirsiniz.</p>
                        </div>
                    </div>
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">Müşteri Randevuları</h3>
                            <p>Müşterilerinizin geçmiş ve gelecekteki randevularını görüntüleyin.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yönetim Section -->
            <div class="my-12" id="management">
                <h2 class="text-4xl font-bold text-white bg-gradient-to-r from-primary to-secondary py-3 px-6 rounded-md shadow-md mb-6 border-b-4 border-accent hover:text-accent transition-all duration-300 ease-in-out">
                    Yönetim
                </h2>
                <p class="text-lg text-gray-500 mb-6">Yönetim panelinizin tüm özellikleri.</p>
                <!-- Management Cards -->
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">Kullanıcı Yönetimi</h3>
                            <p>Kullanıcı ekleyin, düzenleyin ve silin.</p>
                        </div>
                    </div>
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">İzinler</h3>
                            <p>Farklı roller ve izinler oluşturun.</p>
                        </div>
                    </div>
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">Ekip Yönetimi</h3>
                            <p>Ekip üyeleriyle birlikte projeleri yönetebilirsiniz.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Takip Section -->
            <div class="my-12" id="tracking">
                <h2 class="text-4xl font-bold text-white bg-gradient-to-r from-primary to-secondary py-3 px-6 rounded-md shadow-md mb-6 border-b-4 border-accent hover:text-accent transition-all duration-300 ease-in-out">
                    Takip
                </h2>
                <p class="text-lg text-gray-500 mb-6">Takip etmek için sunulan çeşitli araçlar.</p>
                <!-- Tracking Cards -->
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">Sipariş Takibi</h3>
                            <p>Müşteri siparişlerini kolayca takip edin.</p>
                        </div>
                    </div>
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">Proje Takibi</h3>
                            <p>Devam eden projelerinizi izleyin.</p>
                        </div>
                    </div>
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold">Performans Raporları</h3>
                            <p>Ekibinizin ve iş süreçlerinin performansını takip edin.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <section class="py-16 bg-base-200">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-white mb-4">Tüm İşlemleri Tek Bir Sihirli Buton ile Yönetebilirsiniz</h2>
            <p class="text-lg text-gray-500 mb-8">Satış, randevu, yönetim ve takip işlemleri tek bir butonla daha kolay!
                <strong>Haydi başlayın!</strong></p>
            <a href="#sales"
               class="btn btn-primary text-xl px-8 py-4 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-lg shadow-lg hover:scale-105 transform transition-all ease-in-out duration-300">
                Başlayın
            </a>
        </div>
    </section>


    <x-card class="mt-5">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold">Benzersiz Özellikler</h2>
            <p class=mt-2">Temel Özelliklerimizle İşletmenizin Tam Potansiyelini Ortaya Çıkarın</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Müdavim</h3>
                    <p class="mt-2">Hizmet kullandıkça, otomatik hizmet kazansınlar.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Kilitli Taksit</h3>
                    <p class="mt-2">Taksit ödendiğinde hangi hizmetlerin yükleneceğini seçin.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Mesajlaşma</h3>
                    <p class="mt-">Şirket içi konuşmalarınızı güvenle gerçekleştirin.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Online Randevu</h3>
                    <p class="mt-2">Danışanlarınız randevularını online alsın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Online Satış</h3>
                    <p class="mt-2">7/24 satış yapmanın keyfine varın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">İndirim Kuponları</h3>
                    <p class="mt-">Özel günlerde indirim kuponları tanımlayın.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Yetkilendirme</h3>
                    <p class="mt-2">Danışanlarınız randevularını online alsın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Şube Yönetimi</h3>
                    <p class="mt-2">7/24 satış yapmanın keyfine varın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Teklif Oluşturma</h3>
                    <p class="mt-">Özel günlerde indirim kuponları tanımlayın.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Seans İşlemleri</h3>
                    <p class="mt-2">Danışanlarınız randevularını online alsın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Sosyal Medya Talep Yönetimi</h3>
                    <p class="mt-2">7/24 satış yapmanın keyfine varın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Whatsapp Entegrasyonu</h3>
                    <p class="mt-">Özel günlerde indirim kuponları tanımlayın.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Danışan Etiketleri</h3>
                    <p class="mt-2">Danışanlarınız randevularını online alsın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Ödeme Takip</h3>
                    <p class="mt-2">7/24 satış yapmanın keyfine varın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">İnteraktif Anasayfa</h3>
                    <p class="mt-">Özel günlerde indirim kuponları tanımlayın.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Web Sitesi Yönetimi</h3>
                    <p class="mt-2">Sunucu, sertifika, çalışmayan formlardan kurtulun.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Otomatik Yedekleme</h3>
                    <p class="mt-2">7/24 satış yapmanın keyfine varın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Gelişmiş Randevu Yönetimi</h3>
                    <p class="mt-">Özel günlerde indirim kuponları tanımlayın.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Onay Sistemi</h3>
                    <p class="mt-2">Yapılan tüm işlemleri onaya tabi tutun.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Kasa İşlemleri</h3>
                    <p class="mt-2">Muhasebe yazılımları kadar güçlü.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Raporlama</h3>
                    <p class="mt-">Benzersiz raporlama ile mevcut durumunuzu ve geleceğe dair planlarını
                        kuvvetlendirin.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Ürün Satışı ve Stok Takip</h3>
                    <p class="mt-2">Yapılan tüm işlemleri onaya tabi tutun.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Kampanya Modülü ile Geleceği Planlayın</h3>
                    <p class="mt-2">Muhasebe yazılımları kadar güçlü.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Prim Sistemi</h3>
                    <p class="mt-">Kişi bazlı veya genel prim tanımlayın.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Seans Paylaşma</h3>
                    <p class="mt-2">Danışanlarınız sevdikleriyle seanslarını paylaşsın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Kendi Sitenizden Erişim</h3>
                    <p class="mt-2">Muhasebe yazılımları kadar güçlü.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Personel</h3>
                    <p class="mt-">Avans, prim, yıllık izi hepsini kontrol edin.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Parmak İzi</h3>
                    <p class="mt-2">Parmak izi modülü ile işe giriş çıkış saatlerini görün.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Adisyon</h3>
                    <p class="mt-2">Sadeleştirilmiş adisyon sistemi.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Destek Talepleri</h3>
                    <p class="mt-">Şikayet edilmeden önce siz bilgilenin.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">SMS Planlayıcı</h3>
                    <p class="mt-2">İleriye dönük smslerinizi planlayın, günü geldiğinde gitsin.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Erişim Engelleme</h3>
                    <p class="mt-2">Sisteminiz şubeniz kapandığında personele kapatılsın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">IP Kontrol</h3>
                    <p class="mt-">Sisteme sadece sizin belirlediğiniz lokasyonlardan girilsin.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Ajanda</h3>
                    <p class="mt-2">Personelinizin ajandasına hakim olun.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Tam Güncel Websitesi</h3>
                    <p class="mt-2">Siz konuyu belirleyin yapay zeka makaleyi yazıp sitenizde paylaşsın.</p>
                </div>
                <div class="p-6 border rounded-lg">
                    <h3 class="text-xl font-bold">Uçak Bileti Arar Gibi Randevu</h3>
                    <p class="mt-">Sisteme sadece sizin belirlediğiniz lokasyonlardan girilsin.</p>
                </div>
            </div>
        </div>
    </x-card>
    <section class="py-8">

    </section>
    <x-card>
        <div class="w-full max-w-6xl mx-auto py-8 px-4">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold">Fiyatlandırma</h1>
                <p class="mt-2">İhtiyaçlarınıza göre ölçeklenen esnek fiyatlandırmayı keşfedin. Hiçbir gizli ücret yok,
                    başarınız için yalnızca şeffaf seçenekler var.</p>
                <div class="flex justify-center space-x-4 mt-4">
                    <button class="px-4 py-2 rounded-full bg-blue-500 text-white">Aylık</button>
                    <button class="px-4 py-2 rounded-full border border-blue-500 text-blue-500">Yıllık <span
                            class="ml-2 px-2 py-1 bg-yellow-400 rounded-full text-black">-10%</span></button>
                </div>
            </div>

            <!-- Pricing Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Free Plan -->
                <div class="border p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold text-center">Ücretsiz</h2>
                    <p class="text-4xl font-extrabold text-center mt-4">0 ₺<span
                            class="text-lg font-normal text-gray-500">/ay</span></p>
                    <p class="text-center mt-2">Konu sadece randevuysa, o zaten ücretsiz.</p>
                    <ul class="mt-6 space-y-3">
                        <li class="flex items-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3">Sınırsız Kullanıcı</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3">Randevu Yönetimi</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3">Kasa Yönetimi</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3">SMS Sistemi</span>
                        </li>
                    </ul>
                    <button
                        class="w-full mt-6 px-4 py-2 text-blue-500 border border-blue-500 rounded-lg hover:bg-blue-500 hover:text-white">
                        Ücretsiz Kullanın
                    </button>
                </div>

                <!-- Premium Plan -->
                <div class="border p-6 rounded-lg shadow-lg relative">

                    <h2 class="text-2xl font-bold text-center">Plus</h2>
                    <p class="text-4xl font-extrabold text-center mt-4">1999 ₺<span
                            class="text-lg font-normal text-gray-500">/ay</span></p>
                    <p class="text-center mt-2">Plus planımızla gelişmiş özelliklerin kilidini açın.</p>
                    <ul class="mt-6 space-y-3">
                        <li class="flex items-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3">Tüm Özellikler</span>
                        </li>
                    </ul>
                    <button class="w-full mt-6 px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">Daha fazla
                        bilgi al
                    </button>
                </div>

                <!-- Ultimate Plan -->
                <div class="border p-6 rounded-lg shadow-lg relative">
                    <span
                        class="absolute top-0 right-0 bg-yellow-400 text-black px-2 py-1 rounded-bl-lg">En iyi teklif</span>
                    <h2 class="text-2xl font-bold text-center">Pro</h2>
                    <p class="text-4xl font-extrabold text-center mt-4">2999 ₺<span
                            class="text-lg font-normal text-gray-500">/ay</span></p>
                    <p class="text-center mt-2">Pro planımızla iş potansiyelini ortaya çıkarın. Özellikleri,
                        ölçeklenebilirliği ve başarıyı en üst düzeye çıkarın.</p>
                    <ul class="mt-6 space-y-3">
                        <li class="flex items-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3">7/24 Online Satış</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3">Plus Paketteki Tüm Özellikler</span>
                        </li>
                    </ul>
                    <button class="w-full mt-6 px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">Hadi
                        Başlayalım
                    </button>
                </div>
            </div>
        </div>
    </x-card>


    <!-- Navbar -->
    <nav class="bg-white shadow-lg p-4 flex justify-between items-center">
        <div class="text-xl font-bold">SaaS Landing</div>
        <div>
            <a href="#" class="text-blue-500 px-4">Register</a>
            <a href="#" class="text-blue-500 px-4">Login</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto flex flex-col md:flex-row items-center">
            <div class="w-full md:w-1/2">
                <h1 class="text-4xl font-bold">Revolutionize your business with our dynamic SaaS solutions.</h1>
                <p class="mt-4 text-gray-600">Elevate your business to new heights with our cutting-edge SaaS
                    solutions.</p>
                <div class="mt-6">
                    <button class="bg-blue-500 text-white px-6 py-3 rounded-lg">Get Started</button>
                    <button class="text-blue-500 ml-4">Learn More</button>
                </div>
            </div>
            <div class="w-full md:w-1/2 mt-8 md:mt-0">
                <img src="https://via.placeholder.com/500x400" alt="Dashboard">
            </div>
        </div>
    </section>

    <!-- Partner Logos -->
    <section class="py-8 bg-white">
        <div class="container mx-auto flex justify-center space-x-8">
            <img src="https://via.placeholder.com/100x50" alt="Google">
            <img src="https://via.placeholder.com/100x50" alt="Microsoft">
            <img src="https://via.placeholder.com/100x50" alt="Netflix">
            <img src="https://via.placeholder.com/100x50" alt="PayPal">
        </div>
    </section>

    <!-- Features Section -->


    <!-- Quick Integrations -->
    <section class="py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold">Quick Integrations</h2>
            <p class="text-gray-600 mt-2">Effortlessly integrate tools for unified efficiency with our SaaS
                platform.</p>

            <div class="flex justify-center space-x-6 mt-8">
                <img src="https://via.placeholder.com/100x50" alt="Amazon">
                <img src="https://via.placeholder.com/100x50" alt="Slack">
                <img src="https://via.placeholder.com/100x50" alt="Google Drive">
                <img src="https://via.placeholder.com/100x50" alt="Bitbucket">
            </div>
        </div>
    </section>

    <!-- Pricing Plans -->
    <section class="py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold">Pricing Plans</h2>
            <p class="text-gray-600 mt-2">Explore flexible pricing that scales with your needs.</p>
            <div class="mt-8 flex justify-center space-x-4">
                <button class="px-4 py-2 bg-blue-500 text-white rounded-full">Monthly</button>
                <button class="px-4 py-2 border border-blue-500 text-blue-500 rounded-full">Yearly <span
                        class="ml-2 bg-yellow-400 text-black px-2 py-1 rounded-full">-40%</span></button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                <!-- Free Plan -->
                <div class="border p-6 rounded-lg">
                    <h3 class="text-2xl font-bold">Free</h3>
                    <p class="text-4xl font-bold mt-4">$0<span class="text-lg font-normal text-gray-500">/month</span>
                    </p>
                    <p class="mt-2 text-gray-600">Essential features at no cost.</p>
                    <ul class="mt-6 space-y-2">
                        <li>For Personal Use</li>
                        <li>20 products</li>
                        <li>Limited integrations</li>
                    </ul>
                    <button class="w-full mt-6 px-4 py-2 border border-blue-500 text-blue-500 rounded-lg">Start for
                        Free
                    </button>
                </div>

                <!-- Premium Plan -->
                <div class="border p-6 rounded-lg relative">
                    <span
                        class="absolute top-0 right-0 bg-yellow-400 text-black px-2 py-1 rounded-bl-lg">Best Offer</span>
                    <h3 class="text-2xl font-bold">Premium</h3>
                    <p class="text-4xl font-bold mt-4">$99<span class="text-lg font-normal text-gray-500">/month</span>
                    </p>
                    <p class="mt-2 text-gray-600">Advanced features and SaaS experience.</p>
                    <ul class="mt-6 space-y-2">
                        <li>Up to 10 Team Members</li>
                        <li>All Apps unlocked</li>
                        <li>99.9% Support Response</li>
                    </ul>
                    <button class="w-full mt-6 px-4 py-2 bg-blue-500 text-white rounded-lg">Upgrade to Premium</button>
                </div>

                <!-- Ultimate Plan -->
                <div class="border p-6 rounded-lg">
                    <h3 class="text-2xl font-bold">Ultimate</h3>
                    <p class="text-4xl font-bold mt-4">$199<span class="text-lg font-normal text-gray-500">/month</span>
                    </p>
                    <p class="mt-2 text-gray-600">Maximize features and scalability.</p>
                    <ul class="mt-6 space-y-2">
                        <li>Unlimited Members</li>
                        <li>All Apps unlocked</li>
                        <li>24/7 Quick Support</li>
                    </ul>
                    <button class="w-full mt-6 px-4 py-2 bg-blue-500 text-white rounded-lg">Get Ultimate Power</button>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold">FAQs</h2>
            <div class="mt-8 text-left max-w-2xl mx-auto">
                <div tabindex="0" class="collapse collapse-arrow border-b">
                    <input type="checkbox"/>
                    <div class="collapse-title text-lg font-bold">
                        How does SaaS benefit my business?
                    </div>
                    <div class="collapse-content">
                        <p>SaaS offers several advantages including cost-effectiveness, scalability, and ease of
                            use.</p>
                    </div>
                </div>
                <div tabindex="0" class="collapse collapse-arrow border-b">
                    <input type="checkbox"/>
                    <div class="collapse-title text-lg font-bold">
                        What features are included in your SaaS platform?
                    </div>
                    <div class="collapse-content">
                        <p>Our platform includes a variety of tools and integrations.</p>
                    </div>
                </div>
                <!-- Add more FAQ items here -->
            </div>
        </div>
    </section>

    <!-- Footer

    </div>
