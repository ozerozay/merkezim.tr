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
                        <i class="text-2xl text-primary">🎁</i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">{{ __('client.menu_coupon') }}</h2>
                        <p class="text-sm text-base-content/70">Kuponlarınızı görüntüleyin ve kullanın</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-primary">
                        <i class="text-2xl">🎫</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Toplam Kupon</div>
                    <div class="stat-value text-lg">{{ $data->count() }}</div>
                </div>
                
                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-success">
                        <i class="text-2xl">✨</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Aktif Kupon</div>
                    <div class="stat-value text-lg">{{ $data->where('status', 1)->count() }}</div>
                </div>

                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-warning">
                        <i class="text-2xl">⌛️</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Süresi Dolan</div>
                    <div class="stat-value text-lg">{{ $data->where('status', 0)->count() }}</div>
                </div>
            </div>
        </div>

        @if ($data->isEmpty())
            <!-- Boş Durum -->
            <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-8">
                <div class="flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-4">
                        <span class="text-3xl">🎁</span>
                    </div>
                    <h3 class="text-lg font-medium mb-2">Hediye kuponunuz bulunmuyor</h3>
                    <p class="text-sm text-base-content/70">Yeni kuponlar oluşturduğunuzda burada görüntülenecektir.</p>
                </div>
            </div>
        @else
            <!-- Kuponlar Section -->
            <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-1.5 bg-primary/10 rounded-lg">
                        <i class="text-primary text-lg">🎫</i>
                    </div>
                    <h3 class="font-medium">Kuponlarınız</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($data as $coupon)
                        <div class="card bg-base-200/30 p-4 rounded-xl hover:shadow-md transition-all duration-300 cursor-pointer"
                             wire:click="handleClick({{ $coupon->id }})">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <span class="text-lg">🎫</span>
                                    </div>
                                    <div>
                                        <h4 class="font-medium font-mono">{{ $coupon->code }}</h4>
                                        <p class="text-xs text-base-content/50">
                                            {{ $coupon->remainingDay() == 'SÜRESİZ' ? 'SÜRESİZ' : $coupon->remainingDay() . ' gün kaldı' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="badge {{ $coupon->remainingDay() == 'SÜRESİZ' ? 'badge-primary' : 'badge-warning' }} gap-1">
                                    @if ($coupon->discount_type === 'percentage')
                                        %{{ $coupon->discount_amount }}
                                    @else
                                        @price($coupon->discount_amount) TL
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex flex-col gap-2 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-base-content/70">📦 Adet:</span>
                                    <span class="font-medium">{{ $coupon->count }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-base-content/70">🛒 Min. Sipariş:</span>
                                    <span class="font-medium">
                                        {{ $coupon->min_order > 0 ? \App\Traits\LiveHelper::price_text($coupon->min_order) : 'Yok' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
