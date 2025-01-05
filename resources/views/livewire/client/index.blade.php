<?php

new #[\Livewire\Attributes\Layout('components.layouts.client')] class extends \Livewire\Volt\Component {

    #[\Livewire\Attributes\Url]
    public $login;

    public function mount(): void
    {
        if ($this->login) {
            $this->dispatch('slide-over.open', ['component' => 'login.login-page']);
        }
    }

};

?>
<div>
    @php
        $slides = [
            [
                'image' => 'https://picsum.photos/800/400',
                'title' => 'HOŞ GELDİNİZ',
                'description' => '7/24 Tüm işlemlerinizi güvenle gerçekleştirin.',
                'url' => '/docs/installation',
                'urlText' => 'HADİ BAŞLAYALIM',
            ],
            [
                'image' => 'https://picsum.photos/800/300',
                'title' => 'Full stack developers',
                'description' => 'Where burnout is just a fancy term for Tuesday.',
            ],
            [
                'image' => 'https://picsum.photos/800/300',
                'url' => '/docs/installation',
                'urlText' => 'Let`s go!',
            ],
            [
                'image' => 'https://picsum.photos/800/300',
                'url' => '/docs/installation',
            ],
        ];
    @endphp
        <!-- Başlık -->
    <h3 class="text-xl font-semibold text-base-content mb-6 text-center">
        Hoş Geldiniz
    </h3>

    <!-- Kutucuklar -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <!-- Randevu Al -->
        <a href="/randevu-al"
           class="block p-6 bg-blue-500 text-white rounded-lg text-center hover:bg-blue-600 transition">
            <span class="block text-3xl mb-2">📅</span>
            <span class="block text-lg font-semibold">Randevu Al</span>
            <p class="text-sm opacity-80 mt-1">Hızlıca randevu oluşturun.</p>
        </a>

        <!-- Rezervasyon Talep Et -->
        <a href="/rezervasyon-talep-et"
           class="block p-6 bg-green-500 text-white rounded-lg text-center hover:bg-green-600 transition">
            <span class="block text-3xl mb-2">📝</span>
            <span class="block text-lg font-semibold">Rezervasyon Talep Et</span>
            <p class="text-sm opacity-80 mt-1">Özel rezervasyon taleplerinizi iletin.</p>
        </a>

        <!-- Paket Satın Al -->
        <a href="/paket-satin-al"
           class="block p-6 bg-yellow-500 text-white rounded-lg text-center hover:bg-yellow-600 transition">
            <span class="block text-3xl mb-2">🎁</span>
            <span class="block text-lg font-semibold">Paket Satın Al</span>
            <p class="text-sm opacity-80 mt-1">Avantajlı paketlerden faydalanın.</p>
        </a>

        <!-- Kullandıkça Kazan -->
        <a href="/kullandikca-kazan"
           class="block p-6 bg-red-500 text-white rounded-lg text-center hover:bg-red-600 transition">
            <span class="block text-3xl mb-2">💳</span>
            <span class="block text-lg font-semibold">Kullandıkça Kazan</span>
            <p class="text-sm opacity-80 mt-1">Harcamalarınızdan puan kazanın.</p>
        </a>

        <!-- Davet Et Kazan -->
        <a href="/davet-et-kazan"
           class="block p-6 bg-purple-500 text-white rounded-lg text-center hover:bg-purple-600 transition">
            <span class="block text-3xl mb-2">🤝</span>
            <span class="block text-lg font-semibold">Davet Et Kazan</span>
            <p class="text-sm opacity-80 mt-1">Arkadaşlarını davet et, kazan.</p>
        </a>

        <!-- Yol Tarifi -->
        <a href="/yol-tarifi"
           class="block p-6 bg-teal-500 text-white rounded-lg text-center hover:bg-teal-600 transition">
            <span class="block text-3xl mb-2">📍</span>
            <span class="block text-lg font-semibold">Yol Tarifi</span>
            <p class="text-sm opacity-80 mt-1">Size en yakın şubeyi bulun.</p>
        </a>

        <!-- Teklif İste -->
        <a href="/teklif-iste"
           class="block p-6 bg-pink-500 text-white rounded-lg text-center hover:bg-pink-600 transition">
            <span class="block text-3xl mb-2">📤</span>
            <span class="block text-lg font-semibold">Teklif İste</span>
            <p class="text-sm opacity-80 mt-1">Hizmetler için teklif alın.</p>
        </a>

        <!-- Hediye Kuponları -->
        <a href="/hediye-kuponlari"
           class="block p-6 bg-indigo-500 text-white rounded-lg text-center hover:bg-indigo-600 transition">
            <span class="block text-3xl mb-2">🎟️</span>
            <span class="block text-lg font-semibold">Hediye Kuponları</span>
            <p class="text-sm opacity-80 mt-1">Özel indirimlerden yararlanın.</p>
        </a>

        <!-- Diğer İşlemler -->
        <a href="/daha-fazla-islem"
           class="block p-6 bg-gray-500 text-white rounded-lg text-center hover:bg-gray-600 transition">
            <span class="block text-3xl mb-2">✨</span>
            <span class="block text-lg font-semibold">Diğer İşlemler</span>
            <p class="text-sm opacity-80 mt-1">Tüm hizmetlerimizi görüntüleyin.</p>
        </a>
    </div>

    @if (1==2)
        <x-alert title="Değerlendirmediğiniz randevu bulunuyor."
                 description="Görüşleriniz bizim için değerli, bir dakikanızı ayırıp" icon="tabler.star"
                 class="alert-info mb-2">
            <x-slot:actions>
                <x-button label="Değerlendir" />
            </x-slot:actions>
        </x-alert>
        <x-alert title="1500₺ gecikmiş ödemeniz bulunuyor."
                 description=" Kredi kartı veya havale ile hemen ödeyebilirsiniz."
                 icon="tabler.mood-look-down" class="alert-warning mb-2">
            <x-slot:actions>
                <x-button label="Hemen Öde" />
            </x-slot:actions>
        </x-alert>
        <x-alert title="Aktif teklifiniz bulunuyor." description="Size özel fırsatlardan yararlanmak için acele edin."
                 icon="tabler.confetti" class="alert-info mb-2">
            <x-slot:actions>
                <x-button label="Teklifi Gör" />
            </x-slot:actions>
        </x-alert>
        <x-alert title="Referans Kampanyası" description="Arkadaşlarınızı davet edin, ücretsiz seans kazanın."
                 icon="tabler.confetti" class="alert-info mb-2">
            <x-slot:actions>
                <x-button label="Davet Et" />
            </x-slot:actions>
        </x-alert>


        <x-carousel :slides="$slides" class="mt-2" />
    @endif
    @if (1==2)
        <x-card title="En çok tercih edilenler" separator class="mt-5">
            <div class="grid md:grid-cols-2  lg:grid-cols-4 gap-4 mt-5">

                <x-card shadow
                        class="card w-full bg-base-100 shadow-xl hover:shadow-pink-300 hover:shadow-2xl cursor-pointer border border-pink-300"
                        wire:click="handleClick">
                    {{-- TITLE --}}
                    <x-slot:title class="text-lg font-black">
                        8 SEANS EPİLASYON TÜM VÜCUT
                    </x-slot:title>

                    <x-slot:subtitle>
                        @price(150000)
                    </x-slot:subtitle>

                    {{-- MENU --}}
                    <x-slot:menu>
                        <x-button icon="tabler.woman" tooltip="Sepete Ekle" class="btn-sm" spinner />
                    </x-slot:menu>
                    <div class="absolute top-0 right-0 -mt-4 -mr-1">
                        <span class="badge badge-primary p-3 shadow-lg text-sm"> %40 İndirim </span>
                    </div>
                    <div>BAKIRKÖY - BEYLİKDÜZÜ - KADIKÖY - MECİDİYEKÖY</div>
                </x-card>
                <x-card shadow
                        class="card w-full bg-base-100 shadow-xl hover:shadow-blue-300 hover:shadow-2xl cursor-pointer border border-blue-300"
                        wire:click="handleClick">
                    {{-- TITLE --}}
                    <x-slot:title class="text-lg font-black">
                        8 SEANS EPİLASYON TÜM VÜCUT
                    </x-slot:title>

                    <x-slot:subtitle>
                        @price(150000)
                    </x-slot:subtitle>

                    {{-- MENU --}}
                    <x-slot:menu>
                        <x-button icon="tabler.man" tooltip="Sepete Ekle" class="btn-sm" spinner />
                    </x-slot:menu>
                    <div class="absolute top-0 right-0 -mt-4 -mr-1">
                        <span class="badge badge-primary p-3 shadow-lg text-sm"> %40 İndirim </span>
                    </div>
                    <div>BAKIRKÖY - BEYLİKDÜZÜ - KADIKÖY - MECİDİYEKÖY</div>
                </x-card>
                <x-card shadow
                        class="card w-full bg-base-100 shadow-xl hover:shadow-gray-300 hover:shadow-2xl cursor-pointer border border-gray-300"
                        wire:click="handleClick">
                    {{-- TITLE --}}
                    <x-slot:title class="text-lg font-black">
                        8 SEANS EPİLASYON TÜM VÜCUT
                    </x-slot:title>

                    <x-slot:subtitle>
                        @price(150000)
                    </x-slot:subtitle>

                    {{-- MENU --}}
                    <x-slot:menu>
                        <x-button icon="tabler.friends" tooltip="Sepete Ekle" class="btn-sm" spinner />
                    </x-slot:menu>
                    <div class="absolute top-0 right-0 -mt-4 -mr-1">
                        <span class="badge badge-primary p-3 shadow-lg text-sm"> %40 İndirim </span>
                    </div>
                    <div>BAKIRKÖY - BEYLİKDÜZÜ - KADIKÖY - MECİDİYEKÖY</div>
                </x-card>
            </div>
        </x-card>
    @endif
</div>
