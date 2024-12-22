<div>
    <div class="overflow-x-hidden">
        <x-card title="Ödeme Yap" subtitle="Taksit ödemelerinizi güvenle gerçekleştirin." separator progress-indicator>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')"/>
            </x-slot:menu>
            <div class="max-w-md mx-auto space-y-4">
                @if($price_late > 0)
                    <div class="card bg-base-200 shadow-md p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-medium">GECİKMİŞ ÖDEME</h3>
                                <p class="text-xs text-gray-500">Gecikmiş ödemelerinizi ödeyin.</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-primary font-bold text-lg">@price($price_late)</span>
                                <x-button class="btn btn-primary"
                                          wire:click="$dispatch('slide-over.open', {component: 'web.shop.checkout-page', arguments: {'type': '{{\App\Enum\PaymentType::taksit->name}}', 'data': '{{ $price_late }}' }})">
                                    Öde
                                </x-button>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($price_total > 0)
                    <div class="card bg-base-200 shadow-md p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-medium">TOPLAM BORÇ</h3>
                                <p class="text-xs text-gray-500">Tüm taksitlerinizi ödeyin.</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-primary font-bold text-lg">@price($price_total)</span>
                                <x-button class="btn btn-primary"
                                          wire:click="$dispatch('slide-over.open', {component: 'web.shop.checkout-page', arguments: {'type': '{{\App\Enum\PaymentType::taksit->name}}', 'data': '{{ $price_total }}' }})">
                                    Öde
                                </x-button>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-base-200 shadow-md p-4">
                        <h3 class="text-sm font-medium mb-2">KENDİM BELİRLEYECEĞİM</h3>
                        <div>
                            <x-form wire:submit="payManuel">
                                <x-input wire:model="price_manuel" money>
                                    <x-slot:append>
                                        <x-button label="Öde" type="submit" icon="o-check"
                                                  class="btn-primary rounded-s-none"/>
                                    </x-slot:append>
                                </x-input>
                            </x-form>

                        </div>
                    </div>
                @endif
            </div>

        </x-card>
    </div>
</div>
