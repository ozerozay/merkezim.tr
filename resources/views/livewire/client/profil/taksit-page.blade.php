@php
    $seans = [];
@endphp

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
        <!-- Header Section with Stats -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-xl">
                        <i class="text-2xl text-primary">💰</i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">Taksit Ödemeleri</h2>
                        <p class="text-sm text-base-content/70">Tüm taksit ödemelerinizi buradan takip edebilirsiniz</p>
                    </div>
                </div>

                @if ($show_pay)
                    <x-button class="btn-primary" icon="tabler.brand-mastercard"
                            wire:click="$dispatch('slide-over.open', {component: 'web.modal.taksit-payment-modal'})">
                        {{ __('client.page_taksit_pay') }}
                    </x-button>
                @endif
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-primary">
                        <i class="text-2xl">📊</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Toplam Taksit</div>
                    <div class="stat-value text-lg">{{ $data->count() }}</div>
                </div>
                
                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-success">
                        <i class="text-2xl">💵</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Toplam Tutar</div>
                    <div class="stat-value text-lg">@price($data->sum('total'))</div>
                </div>

                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-warning">
                        <i class="text-2xl">⏳</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Kalan Tutar</div>
                    <div class="stat-value text-lg">@price($data->sum('remaining'))</div>
                </div>

                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-success">
                        <i class="text-2xl">✓</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Tamamlanan</div>
                    <div class="stat-value text-lg">{{ $data->where('remaining', 0)->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Active Payments Section -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-1.5 bg-warning/10 rounded-lg">
                    <i class="text-warning text-lg">⚡</i>
                </div>
                <h3 class="font-medium">Aktif Taksitler</h3>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach ($data->where('remaining', '>', 0)->all() as $taksit)
                    <div class="bg-base-200/30 rounded-xl p-4 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                        <span class="text-primary text-lg">📅</span>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium">{{ $taksit->date->format('d/m/Y') }}</h4>
                                    <p class="text-xs opacity-50">{{ $taksit->sale->unique_id }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col items-end gap-1">
                                @if ($taksit->date->lt(\Carbon\Carbon::now()))
                                    <span class="badge badge-error gap-1">Gecikmiş</span>
                                @else
                                    <span class="badge badge-warning gap-1">Bekliyor</span>
                                @endif
                                <span class="text-xs opacity-70">{{ $taksit->date->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- Progress Bar -->
                            <div>
                                <div class="flex justify-between text-sm mb-1.5">
                                    <span class="font-medium">Ödeme Durumu</span>
                                    <span class="opacity-70">{{ round((($taksit->total - $taksit->remaining) / $taksit->total) * 100) }}%</span>
                                </div>
                                <div class="w-full bg-base-300 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ ($taksit->total - $taksit->remaining) / $taksit->total * 100 }}%">
                                    </div>
                                </div>
                                <div class="flex justify-between text-xs mt-1.5">
                                    <span>Ödenen: @price($taksit->total - $taksit->remaining)</span>
                                    <span>Toplam: @price($taksit->total)</span>
                                </div>
                            </div>

                            @if ($taksit->clientTaksitsLocks->isNotEmpty())
                                <div class="border-t border-base-300 pt-4">
                                    <div class="text-sm font-medium mb-2">Hizmetler</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach ($taksit->clientTaksitsLocks as $lock)
                                            <div class="flex justify-between items-center bg-base-200 rounded-lg px-3 py-2">
                                                <span class="text-sm">{{ $lock->service->name }}</span>
                                                <span class="badge badge-primary badge-sm">{{ $lock->quantity }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Completed Payments Section -->
        @if ($show_zero && $data->where('remaining', 0)->isNotEmpty())
            <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-1.5 bg-success/10 rounded-lg">
                        <i class="text-success text-lg">✓</i>
                    </div>
                    <h3 class="font-medium">Tamamlanan Ödemeler</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($data->where('remaining', 0)->all() as $taksit)
                        <div class="bg-base-200/30 rounded-xl p-3 hover:shadow-md transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-success/10 flex items-center justify-center">
                                        <span class="text-success text-sm">✓</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">{{ $taksit->date->format('d/m/Y') }}</p>
                                        <p class="text-xs opacity-50">{{ $taksit->sale->unique_id }}</p>
                                    </div>
                                </div>
                                <div class="text-sm font-medium">@price($taksit->total)</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
