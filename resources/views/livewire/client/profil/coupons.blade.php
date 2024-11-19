<?php

new
#[\Livewire\Attributes\Layout('components.layouts.client')]
#[\Livewire\Attributes\Title('Kuponlarım')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;
};

?>
@php
    $seans = [];
@endphp

<div>
    <x-header title="Kuponlarım" subtitle="İşlem yapmak istediğiniz kupona dokunun." separator progress-indicator/>


    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <x-card shadow
                class="card w-full bg-base-100 cursor-pointer border"
                wire:click="handleClick"
                subtitle="1500TL ve üzeri"
                separator>
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                14854784145
            </x-slot:title>

            {{-- MENU --}}
            <x-slot:menu>
                %60 İNDİRİM
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-error p-3 shadow-lg text-sm"> Son 3 Gün </span>
            </div>
            <div>EPİLASYON, CİLT BAKIMI kategorilerinde geçerli.</div>
        </x-card>
        <x-card shadow
                class="card w-full bg-base-100 cursor-pointer border"
                wire:click="handleClick"
                subtitle="1500TL ve üzeri"
                separator>
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                EFSANE-CUMA
            </x-slot:title>

            {{-- MENU --}}
            <x-slot:menu>
                600₺ İNDİRİM
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-error p-3 shadow-lg text-sm"> Son 3 Gün </span>
            </div>
            <div>EPİLASYON, CİLT BAKIMI kategorilerinde geçerli.</div>
        </x-card>
    </div>
</div>
