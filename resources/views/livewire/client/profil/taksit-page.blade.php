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
    <div class="p-6 bg-base-100 dark:bg-base-200 rounded-lg shadow-lg">
        <!-- Özet Bilgiler -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Toplam -->
            <div class="flex flex-col items-center bg-blue-50 dark:bg-blue-900 rounded-lg p-4 shadow-sm">
                <p class="text-sm font-medium text-blue-600 dark:text-blue-300">Toplam</p>
                <p class="text-2xl font-bold text-blue-800 dark:text-blue-100">₺{{ number_format(1000, 2) }}</p>
            </div>
            <!-- Kalan -->
            <div class="flex flex-col items-center bg-green-50 dark:bg-green-900 rounded-lg p-4 shadow-sm">
                <p class="text-sm font-medium text-green-600 dark:text-green-300">Kalan</p>
                <p class="text-2xl font-bold text-green-800 dark:text-green-100">₺{{ number_format(1000, 2) }}</p>
            </div>
            <!-- Geciken -->
            <div class="flex flex-col items-center bg-red-50 dark:bg-red-900 rounded-lg p-4 shadow-sm">
                <p class="text-sm font-medium text-red-600 dark:text-red-300">Geciken</p>
                <p class="text-2xl font-bold text-red-800 dark:text-red-100">₺{{ number_format(1000, 2) }}</p>
            </div>
        </div>

        <!-- Taksit Kartları -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($data->where('remaining', '>', 0)->all() as $taksit)
                <div class="relative bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg hover:shadow-xl transition">
                    <!-- Tarih ve Açıklama -->
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $taksit->date->format('d/m/Y') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tarih</p>
                    </div>

                    <!-- Ödeme Durumu -->
                    <div class="mb-4">
                        @if (round((($taksit->total - $taksit->remaining) / $taksit->total) * 100) > 0)
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Ödeme Durumu</p>
                            <div class="relative w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div
                                    class="absolute top-0 left-0 h-2 bg-green-500 rounded-full"
                                    style="width: {{ ($taksit->total - $taksit->remaining) / $taksit->total * 100 }}%">
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Kalan ve Toplam -->
                    <div class="grid grid-cols-2 gap-4 text-sm mt-4">
                        <div class="flex flex-col">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kalan</p>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">@price($taksit->remaining)</p>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam</p>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">@price($taksit->total)</p>
                        </div>
                    </div>

                    <!-- Hizmet Listesi -->
                    @if ($taksit->clientTaksitsLocks->isNotEmpty())
                        <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                            <div
                                class="grid grid-cols-2 text-sm border-b border-gray-300 dark:border-gray-600 pb-2 mb-2">
                                <p class="text-gray-500 dark:text-gray-400 font-medium">Hizmet Adı</p>
                                <p class="text-gray-500 dark:text-gray-400 font-medium text-right">Adet</p>
                            </div>
                            @foreach ($taksit->clientTaksitsLocks as $lock)
                                <div class="grid grid-cols-2 text-sm">
                                    <p class="text-gray-800 dark:text-white">{{ $lock->service->name }}</p>
                                    <p class="text-gray-800 dark:text-white text-right">{{ $lock->quantity }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Badge -->
                    @if ($taksit->date->lt(\Carbon\Carbon::now()))
                        <div class="absolute top-4 right-4">
                            <span class="badge badge-error text-xs px-3 py-1">Gecikmiş</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
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
