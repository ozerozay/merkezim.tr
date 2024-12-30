<div>
    <x-header title="{{ __('client.menu_offer') }}" subtitle="{{ __('client.page_offer_subtitle') }}." separator
              progress-indicator>
        @if ($show_request)
            <x-slot:actions>
                <x-button class="btn-primary"
                          wire:click="$dispatch('modal.open', {component: 'web.modal.request-offer-modal'})">
                    ✨ Teklif Al
                </x-button>

            </x-slot:actions>
        @endif
    </x-header>
    <div class="space-y-10">
        {{-- Değerlendirilebilir Teklifler --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($data->filter(fn($offer) => !$offer->hasPaymentActive() && !$offer->isCompleted()) as $offer)
                @php
                    $statusStyles = [
                        'evaluatable' => [
                            'color' => 'bg-indigo-100 dark:bg-indigo-800 border-indigo-300 dark:border-indigo-700 text-indigo-900 dark:text-indigo-100',
                            'emoji' => '✨',
                        ],
                    ];

                    $status = 'evaluatable';
                    $cardColor = $statusStyles[$status]['color'];
                    $emoji = $statusStyles[$status]['emoji'];
                @endphp

                <div x-data="{ expanded: false }"
                     class="relative {{ $cardColor }} rounded-md p-4 shadow-md hover:shadow-lg transition border overflow-hidden">
                    <div @click="expanded = !expanded; if (expanded) confetti({ particleCount: 100, spread: 70 })"
                         x-show="!expanded" x-cloak class="cursor-pointer">
                        <div class="flex flex-col items-center justify-center h-full space-y-4">
                            <p class="text-center text-lg font-semibold text-gray-800 dark:text-white">✨ Teklifi
                                Görüntüle</p>
                            <p class="text-center text-sm text-gray-600 dark:text-gray-400">Bu teklifin detaylarını
                                görmek için hemen tıklayın!</p>
                            <span class="badge bg-red-500 text-white px-3 py-1 rounded-full shadow-md text-sm">Son {{ $offer->remainingDay() }} Gün</span>
                        </div>
                    </div>

                    <div x-show="expanded" x-cloak class="space-y-6">
                        {{-- Teklif Detayları --}}
                        <div class="mb-6 bg-white dark:bg-gray-900 rounded-lg p-5 shadow-md">
                            <h3 class="text-base font-bold text-center text-indigo-600 dark:text-indigo-300 mb-2">🎉 Özel
                                Teklif!</h3>
                            <div class="text-center mb-4">
                                <span
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">Teklif ID: {{ $offer->unique_id }}</span>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-semibold text-orange-600 dark:text-orange-300">@price($offer->price)</p>
                            </div>
                        </div>

                        {{-- Hizmet Listesi --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-6">
                            @foreach ($offer->items as $item)
                                <div class="flex items-center bg-gray-50 dark:bg-gray-800 rounded-lg p-2 shadow-sm">
                                    {{-- Sol: Miktar --}}
                                    <div
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-500 text-white font-bold">
                                        {{ $item->quantity }}
                                    </div>

                                    {{-- Sağ: Hizmet Adı ve Kategorisi --}}
                                    <div class="ml-3 truncate">
                                        <p class="text-sm font-medium truncate">{{ $item->itemable->name }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $item->itemable->category->name ?? 'Paket' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Alt Aksiyon Bölümü --}}
                        <div class="mt-4">
                            <p class="mt-2 text-sm text-center text-gray-600 dark:text-gray-300">Bu fırsat size özel
                                olarak sunulmuştur.</p>
                            <button
                                class="btn w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg shadow"
                                wire:click="pay({{ $offer->id }})">
                                ✨ Teklifi Değerlendir
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Diğer Durumlar (Tamamlanan ve Ödeme Bekleyen) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($data->filter(fn($offer) => $offer->hasPaymentActive() || $offer->isCompleted()) as $offer)
                @php
                    $statusStyles = [
                        'processing' => [
                            'color' => 'bg-orange-100 dark:bg-orange-800 border-orange-300 dark:border-orange-700 text-orange-900 dark:text-orange-100',
                            'emoji' => '⏳',
                        ],
                        'completed' => [
                            'color' => 'bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100',
                            'emoji' => '✅',
                        ],
                    ];

                    $status = $offer->hasPaymentActive() ? 'processing' : 'completed';
                    $cardColor = $statusStyles[$status]['color'];
                    $emoji = $statusStyles[$status]['emoji'];
                @endphp

                <div x-data="{ expanded: true }"
                     class="relative {{ $cardColor }} rounded-md p-4 shadow-md hover:shadow-lg transition border overflow-hidden">
                    <div x-show="expanded" x-cloak class="space-y-6">
                        {{-- Teklif Detayları --}}
                        <div class="mb-6 bg-white dark:bg-gray-900 rounded-lg p-5 shadow-md">
                            <h3 class="text-base font-bold text-center text-indigo-600 dark:text-indigo-300 mb-2">🎉 Özel
                                Teklif!</h3>
                            <div class="text-center mb-4">
                                <span
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">Teklif ID: {{ $offer->unique_id }}</span>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-semibold text-orange-600 dark:text-orange-300">@price($offer->price)</p>
                            </div>
                        </div>

                        <div class="absolute top-3 right-3">
                            <span class="badge bg-red-500 text-white px-3 py-1 rounded-full shadow-md text-xs">{{ $offer->remainingDay() }} Gün</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-6">
                            @foreach ($offer->items as $item)
                                <div class="flex items-center bg-gray-50 dark:bg-gray-800 rounded-lg p-2 shadow-sm">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-500 text-white font-bold">
                                        {{ $item->quantity }}
                                    </div>
                                    <div class="ml-3 truncate">
                                        <p class="text-sm font-medium truncate">{{ $item->itemable->name }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $item->itemable->category->name ?? 'Paket' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            @if ($offer->hasPaymentActive())
                                <p class="text-xs text-center font-medium text-orange-700 dark:text-orange-300">⏳ Ödeme
                                    İşlemi Devam Ediyor</p>
                            @elseif ($offer->isCompleted())
                                <p class="text-sm text-center font-medium text-green-700 dark:text-green-300">🎉
                                    Hizmetleriniz aktif edildi! Mutlu günlerde kullanın. 🌟</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>


</div>
