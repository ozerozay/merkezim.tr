<div>
    <x-header title="🎁 {{ __('client.menu_coupon') }}" separator progress-indicator/>
    @if ($data->isEmpty())
        <div class="flex flex-col items-center justify-center h-full p-8 bg-base-200 text-center">
            <!-- Emoji -->
            <div class="text-5xl mb-4">🎁</div>
            <!-- Başlık -->
            <h2 class="text-lg font-semibold mb-2">Hediye kuponunuz bulunmuyor</h2>
            <!-- Açıklama -->
            <p class="text-sm text-gray-500 mb-6">
                Yeni kuponlar oluşturduğunuzda burada görüntülenecektir.
            </p>
        </div>

    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($data as $coupon)
                @php
                    // Renk ve emoji ayarları
                    $cardClass = 'bg-base-100 dark:bg-gray-800'; // Kupon için kart rengi
                    $textClass = 'text-gray-800 dark:text-gray-200'; // Kupon yazı rengi
                @endphp

                <div
                    class="relative {{ $cardClass }} border rounded-lg shadow-lg p-4 cursor-pointer hover:shadow-xl transition"
                    wire:click="handleClick({{ $coupon->id }})">
                    <!-- Kupon Kodu -->
                    <h2 class="text-lg font-bold mb-4 flex items-center space-x-2">
                        <span>🎫</span>
                        <span class="{{ $textClass }} font-mono">{{ $coupon->code }}</span>
                    </h2>

                    <!-- İndirim Bilgisi -->
                    <div class="flex justify-between items-center text-sm mb-2">
                        <span>💸 İndirim:</span>
                        <span class="font-bold">
                    @if ($coupon->discount_type === 'percentage')
                                %{{ $coupon->discount_amount }}
                            @else
                                @price($coupon->discount_amount) TL
                            @endif
                </span>
                    </div>

                    <!-- Adet Bilgisi -->
                    <div class="flex justify-between items-center text-sm mb-2">
                        <span>📦 Adet:</span>
                        <span class="font-bold">{{ $coupon->count }}</span>
                    </div>

                    <!-- Minimum Sipariş Tutarı -->
                    <div class="flex justify-between items-center text-sm mb-2">
                        <span>🛒 Minimum Sipariş:</span>
                        <span class="font-bold">
                    {{ $coupon->min_order > 0 ? \App\Traits\LiveHelper::price_text($coupon->min_order)  : 'Yok' }}
                </span>
                    </div>

                    <!-- Son Gün Badge -->
                    <div class="absolute top-4 right-4">
                <span class="badge badge-error text-xs font-bold px-3 py-2 shadow">
                    {{ $coupon->remainingDay() == 'SÜRESİZ' ? 'SÜRESİZ' : $coupon->remainingDay() . ' gün' }}
                </span>
                    </div>
                </div>
            @endforeach
        </div>

    @endif
</div>
