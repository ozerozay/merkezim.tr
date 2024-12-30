@php
    $seans = [];
@endphp

<div>
    <x-header title="{{ __('client.menu_payments') }}" separator progress-indicator>
        @if ($show_pay)
            <x-slot:actions>
                <x-button class="btn-primary" icon="tabler.brand-mastercard"
                          wire:click="$dispatch('slide-over.open', {component: 'web.modal.taksit-payment-modal'})">
                    {{ __('client.page_taksit_pay') }}
                </x-button>
            </x-slot:actions>
        @endif
    </x-header>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4 bg-base-100 dark:bg-base-200">
        @foreach ($data->where('remaining', '>', 0)->all() as $taksit)
            <div
                class="relative rounded-lg p-4 shadow-md border border-base-300 bg-base-100 dark:bg-base-200 hover:shadow-lg transition">
                <!-- Tarih ve Açıklama -->
                <div class="mb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Tarih</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $taksit->date->format('d/m/Y') }}</p>
                </div>

                <!-- Ödeme Durumu -->
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Ödeme Durumu</p>
                    <!-- İlerleme Çubuğu -->
                    <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                        <div
                            class="bg-green-500 text-xs font-medium text-white text-center p-1 leading-none rounded-full"
                            style="width: {{ ($taksit->total - $taksit->remaining) / $taksit->total * 100 }}%">
                            {{ round((($taksit->total - $taksit->remaining) / $taksit->total) * 100) }}%
                        </div>
                    </div>
                    <!-- Kalan ve Toplam -->
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-500 dark:text-gray-400">Kalan: @price($taksit->remaining)</span>
                        <span class="text-gray-500 dark:text-gray-400">Toplam: @price($taksit->total)</span>
                    </div>
                </div>

                <!-- Unique ID -->
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Taksit ID</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $taksit->sale->unique_id }}</p>
                </div>

                @if ($taksit->clientTaksitsLocks->isNotEmpty())
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 shadow-md mt-4">
                        <!-- Tablo Başlığı -->
                        <div class="grid grid-cols-2 border-b border-gray-300 dark:border-gray-700 pb-2 mb-2">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Hizmet Adı</p>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 text-right">Adet</p>
                        </div>

                        <!-- Hizmetler Listesi -->
                        <div class="space-y-2">
                            @foreach ($taksit->clientTaksitsLocks as $lock)
                                <div class="grid grid-cols-2">
                                    <!-- Hizmet Adı -->
                                    <p class="text-sm text-gray-800 dark:text-white">{{ $lock->service->name }}</p>
                                    <!-- Adet -->
                                    <p class="text-sm text-gray-800 dark:text-white text-right">{{ $lock->quantity }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif


                <!-- Gecikmiş veya Bekleyen Badge -->
                @if ($taksit->date->lt(\Carbon\Carbon::now()))
                    <div class="absolute top-0 right-0 -mt-4 -mr-1">
                        <span class="badge badge-error p-3 shadow-lg text-sm">Gecikmiş</span>
                    </div>
                @elseif (1 == 2)
                    <div class="absolute top-0 right-0 -mt-4 -mr-1">
                        <span class="badge badge-warning p-3 shadow-lg text-sm">Bekleniyor</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if ($show_zero)
        <x-hr/>
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
