<div>
    <x-card title="Ödeme Yap" subtitle="Taksit ödemelerinizi güvenle gerçekleştirin." separator progress-indicator>
        <x-slot:menu>
            <x-button icon="tabler.x" class="btn-sm btn-ghost" wire:click="$dispatch('slide-over.close')" />
        </x-slot:menu>

        <div class="max-w-lg mx-auto space-y-6">
            <!-- Gecikmiş Ödemeler -->
            @if($price_late > 0)
                <div class="card bg-error/5 shadow-sm border border-error/10 hover:shadow-md transition-all duration-300">
                    <div class="card-body p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-error/10 rounded-xl">
                                <i class="text-xl text-error">⚠️</i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-error">Gecikmiş Ödemeler</h3>
                                <p class="text-xs text-base-content/70">Lütfen gecikmiş ödemelerinizi en kısa sürede tamamlayın</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <p class="text-xs text-base-content/70">Toplam Tutar</p>
                                    <p class="text-lg font-semibold text-error">@price($price_late)</p>
                                </div>
                                <x-button class="btn-error" 
                                    wire:click="$dispatch('slide-over.open', {
                                        component: 'web.shop.checkout-page', 
                                        arguments: {
                                            'type': '{{\App\Enum\PaymentType::taksit->name}}', 
                                            'data': '{{ $price_late }}'
                                        }
                                    })">
                                    Öde
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Toplam Borç -->
            @if ($price_total > 0)
                <div class="card bg-primary/5 shadow-sm border border-primary/10 hover:shadow-md transition-all duration-300">
                    <div class="card-body p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-primary/10 rounded-xl">
                                <i class="text-xl text-primary">💰</i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-primary">Toplam Borç</h3>
                                <p class="text-xs text-base-content/70">Tüm taksitlerinizi tek seferde ödeyebilirsiniz</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <p class="text-xs text-base-content/70">Toplam Tutar</p>
                                    <p class="text-lg font-semibold text-primary">@price($price_total)</p>
                                </div>
                                <x-button class="btn-primary"
                                    wire:click="$dispatch('slide-over.open', {
                                        component: 'web.shop.checkout-page', 
                                        arguments: {
                                            'type': '{{\App\Enum\PaymentType::taksit->name}}', 
                                            'data': '{{ $price_total }}'
                                        }
                                    })">
                                    Öde
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Özel Tutar -->
                <div class="card bg-base-100 shadow-sm border border-base-200 hover:shadow-md transition-all duration-300">
                    <div class="card-body p-4">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-base-200 rounded-xl">
                                <i class="text-xl">✏️</i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold">Özel Tutar</h3>
                                <p class="text-xs text-base-content/70">Ödemek istediğiniz tutarı belirleyin</p>
                            </div>
                        </div>

                        <x-form wire:submit="payManuel" class="flex gap-2">
                            <div class="flex-1">
                                <x-input wire:model="price_manuel" 
                                    money 
                                    placeholder="Tutar giriniz"
                                    class="w-full" />
                            </div>
                            <x-button label="Öde" 
                                type="submit" 
                                icon="tabler.check"
                                class="btn-primary" />
                        </x-form>
                    </div>
                </div>
            @endif
        </div>
    </x-card>
</div>
