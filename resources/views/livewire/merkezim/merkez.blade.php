<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
    #[Layout('components.layouts.main')]
    class extends Component {};
?>
<div>
    <x-header title="MARGE GÜZELLİK MERKEZİ" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <div class="grid grid-cols-2 gap-1">
                <x-rating wire:model="ranking4" class="!mask-heart bg-secondary rating-m" />
                <p class="text-xl">4.9 / 5.0</p>
            </div>

        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Mesaj Gönder" icon="o-chat-bubble-left" class="btn-outline" />
            <x-button label="0850 241 1010" icon="o-phone" class="btn-outline" responsive />
            <x-button label="Whatsapp" icon="tabler.brand-whatsapp" class="btn-outline" responsive />
        </x-slot:actions>
    </x-header>
    <div class="flex mt-16">
        <!-- Sol Kart -->
        <div class="w-2/3 p-6 bg-base-200">
            <div class="card bg-base-100 shadow-m col-span-2">
                <figure>
                    @php
                    $slides = [
                    [
                    'image' => 'https://placehold.co/600x400',
                    ],
                    [
                    'image' => '/photos/photo-1565098772267-60af42b81ef2.jpg',
                    ],
                    [
                    'image' => '/photos/photo-1559703248-dcaaec9fab78.jpg',
                    ],
                    [
                    'image' => '/photos/photo-1572635148818-ef6fd45eb394.jpg',
                    ],
                    ];
                    @endphp
    
                    <x-carousel :slides="$slides" />
                </figure>
                <div class="card-body">
                    <h2 class="card-title text-justify">
                        "Sivas’ta her türlü güzellik hizmetlerini sunan işletmemiz 2 şubesi ve profesyonel kadrosuyla
                        sizlerle. Hijyen, kalite, konfor ve müşteri memnuniyetini ön planda tutan işletmemiz, her zaman
                        yenilikçi ve inovasyon odaklı hizmet sunma anlayışı içindedir. Sizlerden aldığımız motivasyonla her
                        zaman daha iyi ve daha kaliteli işlemleri sizlere getirmeye devam edeceğiz. Sağlık ve mutlulukla
                        kalın."
                    </h2>
                    <x-hr />
                </div>
            </div>
        </div>
      
        <!-- Sağ Kart -->
        <div class="w-1/3 p-6 bg-base-300 sticky top-16">
            <div class="card bg-base-100 shadow-m">
                <x-tabs wire:model="selectedTab">
                    <x-tab name="users-tab" label="KADIN BÖLÜMÜ">
                        <x-slot:label>
                            26
                            <x-badge value="3" class="badge-primary" />
                        </x-slot:label>
    
                        <div>
                            <x-accordion wire:model="group">
                                <x-collapse name="group1">
                                    <x-slot:heading>Group 1</x-slot:heading>
                                    <x-slot:content>Hello 1</x-slot:content>
                                </x-collapse>
                                <x-collapse name="group2">
                                    <x-slot:heading>Group 2</x-slot:heading>
                                    <x-slot:content>Hello 2</x-slot:content>
                                </x-collapse>
                                <x-collapse name="group3">
                                    <x-slot:heading>Group 3</x-slot:heading>
                                    <x-slot:content>Hello 3</x-slot:content>
                                </x-collapse>
                            </x-accordion>
                        </div>
                    </x-tab>
                    <x-tab name="tricks-tab" label="ERKEK BÖLÜMÜ">
                        <div>Tricks</div>
                    </x-tab>
                </x-tabs>
            </div>
        </div>
      </div>
    <div class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">

   
      
    </div>

</div>