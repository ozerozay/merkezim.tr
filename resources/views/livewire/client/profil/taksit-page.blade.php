@php
    $seans = [];
@endphp

<div>
    <x-header title="{{ __('client.menu_payments') }}" separator progress-indicator>
        @if ($show_pay)
            <x-slot:actions>
                <x-button class="btn-primary" icon="tabler.brand-mastercard">
                    {{ __('client.page_taksit_pay') }}
                </x-button>
            </x-slot:actions>
        @endif
    </x-header>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($data->where('remaining', '>', 0)->all() as $taksit)
            <x-card shadow class="mb-2 card w-full bg-base-100 border" subtitle="{{ $taksit->sale->unique_id }}">
                {{-- TITLE --}}
                <x-slot:title class="text-lg font-black">
                    {{ $taksit->date->format('d/m/Y') }}
                </x-slot:title>

                {{-- MENU --}}
                <x-slot:menu>
                    @price($taksit->remaining) / @price($taksit->total)
                </x-slot:menu>
                @if ($taksit->date->lt(\Carbon\Carbon::now()))
                    <div class="absolute top-0 right-0 -mt-4 -mr-1">
                        <span class="badge badge-error p-3 shadow-lg text-sm"> Gecikmiş </span>
                    </div>
                @else
                    <div class="absolute top-0 right-0 -mt-4 -mr-1">
                        <span class="badge badge-warning p-3 shadow-lg text-sm"> Bekleniyor </span>
                    </div>
                @endif
            </x-card>
        @endforeach
    </div>
    @if ($show_zero)
        <x-hr />
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($data->where('remaining', 0)->all() as $taksit)
                <x-card shadow class="card w-full bg-base-100 cursor-pointer border" wire:click="handleClick"
                    subtitle="{{ $taksit->sale->unique_id }}">
                    {{-- TITLE --}}
                    <x-slot:title class="text-lg font-black">
                        {{ $taksit->date->format('d/m/Y') }}
                    </x-slot:title>

                    {{-- MENU --}}
                    <x-slot:menu>
                        @price($taksit->total)
                    </x-slot:menu>
                    <div class="absolute top-0 right-0 -mt-4 -mr-1">
                        <span class="badge badge-success p-3 shadow-lg text-sm"> Ödendi </span>
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif


</div>
