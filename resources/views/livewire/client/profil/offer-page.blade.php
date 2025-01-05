<div class="relative text-base-content p-2 min-h-[200px]">
    <!-- Loading Indicator -->
    <div wire:loading class="absolute inset-0 bg-base-200/50 backdrop-blur-sm rounded-lg z-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex flex-col items-center gap-2">
                <span class="loading loading-spinner loading-md text-primary"></span>
                <span class="text-sm text-base-content/70">Yükleniyor...</span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="h-full flex flex-col">
        <!-- Header Section -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-xl">
                        <i class="text-2xl text-primary">✨</i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">{{ __('client.menu_offer') }}</h2>
                        <p class="text-sm text-base-content/70">{{ __('client.page_offer_subtitle') }}</p>
                    </div>
                </div>
                @if ($show_request)
                    <x-button class="btn-primary" wire:click="$dispatch('modal.open', {component: 'web.modal.request-offer-modal'})">
                        ✨ Teklif Al
                    </x-button>
                @endif
            </div>
        </div>

        <!-- Değerlendirilebilir Teklifler -->
        @if ($data->filter(fn($offer) => !$offer->hasPaymentActive() && !$offer->isCompleted())->isNotEmpty())
            <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-1.5 bg-primary/10 rounded-lg">
                        <i class="text-primary text-lg">🎯</i>
                    </div>
                    <h3 class="font-medium">Değerlendirilebilir Teklifler</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($data->filter(fn($offer) => !$offer->hasPaymentActive() && !$offer->isCompleted()) as $offer)
                        <div x-data="{ expanded: false }" 
                             class="card bg-base-200/30 p-4 rounded-xl hover:shadow-md transition-all duration-300">
                            <!-- Kapalı Durum -->
                            <div x-show="!expanded" @click="expanded = !expanded; if (expanded) confetti({ particleCount: 100, spread: 70 })"
                                 class="flex flex-col items-center justify-center gap-3 cursor-pointer">
                                <span class="text-2xl">✨</span>
                                <div class="text-center">
                                    <h4 class="font-medium">Teklifi Görüntüle</h4>
                                    <p class="text-sm text-base-content/70">Detayları görmek için tıklayın</p>
                                </div>
                                <div class="badge badge-warning">Son {{ $offer->remainingDay() }} Gün</div>
                            </div>

                            <!-- Açık Durum -->
                            <div x-show="expanded" x-cloak class="space-y-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium">Teklif ID: {{ $offer->unique_id }}</h4>
                                        <p class="text-2xl font-bold text-primary mt-1">@price($offer->price)</p>
                                    </div>
                                    <div class="badge badge-warning">{{ $offer->remainingDay() }} Gün</div>
                                </div>

                                <div class="space-y-2">
                                    @foreach ($offer->items as $item)
                                        <div class="flex items-center gap-3 bg-base-100 rounded-lg p-2">
                                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center font-medium">
                                                {{ $item->quantity }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium truncate">{{ $item->itemable->name }}</p>
                                                <p class="text-xs text-base-content/70 truncate">{{ $item->itemable->category->name ?? 'Paket' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="btn btn-primary btn-block" wire:click="pay({{ $offer->id }})">
                                    ✨ Teklifi Değerlendir
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Diğer Teklifler -->
        @if ($data->filter(fn($offer) => $offer->hasPaymentActive() || $offer->isCompleted())->isNotEmpty())
            <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-1.5 bg-success/10 rounded-lg">
                        <i class="text-success text-lg">📋</i>
                    </div>
                    <h3 class="font-medium">Diğer Teklifler</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($data->filter(fn($offer) => $offer->hasPaymentActive() || $offer->isCompleted()) as $offer)
                        <div class="card bg-base-200/30 p-4 rounded-xl">
                            <div class="space-y-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium">Teklif ID: {{ $offer->unique_id }}</h4>
                                        <p class="text-2xl font-bold text-primary mt-1">@price($offer->price)</p>
                                    </div>
                                    <div class="badge {{ $offer->hasPaymentActive() ? 'badge-warning' : 'badge-success' }}">
                                        {{ $offer->hasPaymentActive() ? '⏳ İşlemde' : '✅ Tamamlandı' }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    @foreach ($offer->items as $item)
                                        <div class="flex items-center gap-3 bg-base-100 rounded-lg p-2">
                                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center font-medium">
                                                {{ $item->quantity }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium truncate">{{ $item->itemable->name }}</p>
                                                <p class="text-xs text-base-content/70 truncate">{{ $item->itemable->category->name ?? 'Paket' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if ($offer->hasPaymentActive())
                                    <p class="text-sm text-center text-warning">⏳ Ödeme İşlemi Devam Ediyor</p>
                                @else
                                    <p class="text-sm text-center text-success">🎉 Hizmetleriniz aktif edildi!</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
