<?php

new
#[\Livewire\Attributes\Layout('components.layouts.client')]
class extends \Livewire\Volt\Component {

};

?>
<div>
    @php
        $slides = [
            [
                'image' => 'https://picsum.photos/800/400',
                'title' => "HOŞ GELDİNİZ",
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
    <x-alert title="Değerlendirmediğiniz randevu bulunuyor."
             description="Görüşleriniz bizim için değerli, bir dakikanızı ayırıp"
             icon="tabler.star" class="alert-info mb-2">
        <x-slot:actions>
            <x-button label="Değerlendir"/>
        </x-slot:actions>
    </x-alert>
    <x-alert title="1500₺ gecikmiş ödemeniz bulunuyor."
             description=" Kredi kartı veya havale ile hemen ödeyebilirsiniz."
             icon="tabler.mood-look-down" class="alert-warning mb-2">
        <x-slot:actions>
            <x-button label="Hemen Öde"/>
        </x-slot:actions>
    </x-alert>
    <x-alert title="Aktif teklifiniz bulunuyor."
             description="Size özel fırsatlardan yararlanmak için acele edin."
             icon="tabler.confetti" class="alert-info mb-2">
        <x-slot:actions>
            <x-button label="Teklifi Gör"/>
        </x-slot:actions>
    </x-alert>
    <x-alert title="Referans Kampanyası"
             description="Arkadaşlarınızı davet edin, ücretsiz seans kazanın."
             icon="tabler.confetti" class="alert-info mb-2">
        <x-slot:actions>
            <x-button label="Davet Et"/>
        </x-slot:actions>
    </x-alert>

    <x-carousel :slides="$slides" class="mt-2"/>

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
                    <x-button
                        icon="tabler.woman"
                        tooltip="Sepete Ekle"
                        class="btn-sm"
                        spinner
                    />
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
                    <x-button
                        icon="tabler.man"
                        tooltip="Sepete Ekle"
                        class="btn-sm"
                        spinner
                    />
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
                    <x-button
                        icon="tabler.friends"
                        tooltip="Sepete Ekle"
                        class="btn-sm"
                        spinner
                    />
                </x-slot:menu>
                <div class="absolute top-0 right-0 -mt-4 -mr-1">
                    <span class="badge badge-primary p-3 shadow-lg text-sm"> %40 İndirim </span>
                </div>
                <div>BAKIRKÖY - BEYLİKDÜZÜ - KADIKÖY - MECİDİYEKÖY</div>
            </x-card>
        </div>
    </x-card>

</div>

