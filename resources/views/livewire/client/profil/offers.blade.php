<?php

new
#[\Livewire\Attributes\Layout('components.layouts.client')]
#[\Livewire\Attributes\Title('Tekliflerim')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;
};

?>
@php
    $seans = [];
@endphp

<div>
    <x-header title="Tekliflerim" subtitle="İşlem yapmak istediğiniz teklife dokunun." separator progress-indicator>
        <x-slot:actions>
            <x-button class="btn-primary" icon="o-plus">
                Teklif İste
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
                Size Özel Teklif
            </x-slot:title>

            {{-- MENU --}}
            <x-slot:menu>
                @price(1500)
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-error p-3 shadow-lg text-sm"> Son 3 Gün </span>
            </div>
            <div>TÜM BACAK(8), KOLTUK ALTI(6), FALAN FİLAN(4)</div>
        </x-card>
        <x-card shadow
                class="card w-full bg-base-100 cursor-pointer border"
                wire:click="handleClick"
                separator>
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                Size Özel Teklif
            </x-slot:title>

            {{-- MENU --}}
            <x-slot:menu>
                @price(1500)
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-error p-3 shadow-lg text-sm"> Son 3 Gün </span>
            </div>
            <div>TÜM BACAK(8), KOLTUK ALTI(6), FALAN FİLAN(4)</div>
        </x-card>
    </div>
</div>
