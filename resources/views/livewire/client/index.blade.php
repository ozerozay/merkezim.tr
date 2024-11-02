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

    <x-carousel :slides="$slides"/>

    <x-card title="En çok tercih edilenler" separator class="mt-5"/>
    <div class="grid md:grid-cols-2  lg:grid-cols-4 gap-8 mt-5">
        <x-card shadow class="dark:border dark:border-gray-700">
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                @price(15000)
            </x-slot:title>

            {{-- MENU --}}
            <x-slot:menu>
                <x-button
                    icon="o-heart"
                    tooltip="Wishlist"
                    spinner
                />
            </x-slot:menu>

            <div class="line-clamp-1">EPİLASYON TÜM VÜCUT</div>
        </x-card>
    </div>
</div>

