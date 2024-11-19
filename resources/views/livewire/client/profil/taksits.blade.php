<?php

use OpenAI\Laravel\Facades\OpenAI;

new
#[\Livewire\Attributes\Layout('components.layouts.client')]
#[\Livewire\Attributes\Title('Taksitlerim')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    public function handleClick()
    {

        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => 'Hello!'],
            ],
        ]);
        //zouwa9afk6a9s5wy9085
        dump($result->choices[0]->message->content); // Hello! How can I assist you today?
    }
};

?>
@php
    $seans = [];
@endphp

<div>
    <x-header title="Taksitlerim" subtitle="İşlem yapmak takside kupona dokunun." separator progress-indicator>
        <x-slot:actions>
            <x-button class="btn-primary" icon="tabler.brand-mastercard">
                Ödeme Yap
            </x-button>
        </x-slot:actions>
    </x-header>


    <div class="grid grid-cols-1 md:grid-cols-4  gap-4">
        <x-card shadow
                class="card w-full bg-base-100 cursor-pointer border"
                wire:click="handleClick"
                subtitle="894545841">
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                30/08/2018
            </x-slot:title>

            {{-- MENU --}}
            <x-slot:menu>
                @price(1500)
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-error p-3 shadow-lg text-sm"> Gecikmiş </span>
            </div>
        </x-card>
        <x-card shadow
                class="card w-full bg-base-100 cursor-pointer border"
                wire:click="handleClick"
                subtitle="894545841">
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                30/08/2018
            </x-slot:title>

            {{-- MENU --}}
            <x-slot:menu>
                @price(1500)
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-primary p-3 shadow-lg text-sm"> Bekleniyor </span>
            </div>
            EPİLASYON 1 - TÜM BACAK 2
        </x-card>
        <x-card shadow
                class="card w-full bg-base-100 cursor-pointer border"
                wire:click="handleClick"
                subtitle="894545841">
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                30/08/2018
            </x-slot:title>

            {{-- MENU --}}
            <x-slot:menu>
                @price(1500)
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-success p-3 shadow-lg text-sm"> Ödendi </span>
            </div>
        </x-card>
    </div>
</div>
