<?php

new
#[\Livewire\Attributes\Layout('components.layouts.client')]
#[\Livewire\Attributes\Title('Randevularım')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;
};

?>
@php
    $seans = [];
@endphp

<div>
    <x-header title="Randevularım" subtitle="İşlem yapmak istediğiniz randvuya dokunun." separator progress-indicator>
        <x-slot:actions>
            <x-button class="btn-primary" icon="o-plus">
                Randevu Oluştur
            </x-button>
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <x-card shadow
                class="card w-full bg-base-100 cursor-pointer border"
                wire:click="handleClick"
                separator>
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                30/08/2018
            </x-slot:title>

            <x-slot:subtitle>
                MECİDİYEKÖY
            </x-slot:subtitle>

            {{-- MENU --}}
            <x-slot:menu>
                14:00 - 16:00
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-warning p-3 shadow-lg text-sm"> Bekleniyor </span>
            </div>
            <div>TÜM BACAK, KOLTUK ALTI, FALAN FİLAN</div>
            <x-slot:actions>
                <x-button label="İptal Et" icon="tabler.x" class="btn-error btn-outline btn-sm"/>
            </x-slot:actions>
        </x-card>
        <x-card shadow
                class="card w-full bg-base-100 cursor-pointer border"
                wire:click="handleClick"
                separator>
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                30/08/2018
            </x-slot:title>

            <x-slot:subtitle>
                MECİDİYEKÖY
            </x-slot:subtitle>

            {{-- MENU --}}
            <x-slot:menu>
                14:00 - 16:00
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-warning p-3 shadow-lg text-sm"> Yönlendirildi </span>
            </div>
            <div>TÜM BACAK, KOLTUK ALTI, FALAN FİLAN</div>
        </x-card>
    </div>
    <x-hr/>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <x-card shadow
                class="card w-full bg-base-100 cursor-pointer border"
                wire:click=" handleClick
        "
                separator>
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                30/08/2018
            </x-slot:title>

            <x-slot:subtitle>
                MECİDİYEKÖY
            </x-slot:subtitle>

            {{-- MENU --}}
            <x-slot:menu>
                14:00 - 16:00
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-success p-3 shadow-lg text-sm"> Bitti </span>
            </div>
            <div>TÜM BACAK, KOLTUK ALTI, FALAN FİLAN</div>
            <x-hr/>
            <div class="text-center">
                <x-button label="Değerlendir & Bahşiş" icon="tabler.star" class="btn-success btn-outline"/>
            </div>

        </x-card>

    </div>
</div>
