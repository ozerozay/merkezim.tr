<div>
    <div class="overflow-x-hidden">
        <x-card title="Sepetim" subtitle="Hizmetlerinizi düzenleyin" separator progress-indicator>
            <x-slot:menu>
                <x-button 
                    icon="tabler.x" 
                    class="btn-sm btn-ghost" 
                    wire:click="$dispatch('slide-over.close')"
                />
            </x-slot:menu>

            @if ($cart->isEmpty())
                <x-empty-state 
                    title="Sepetiniz Boş"
                    description="Sepetinizde henüz ürün bulunmuyor."
                    icon="tabler.shopping-cart-off">
                    <x-slot:actions>
                        <x-button 
                            label="Alışverişe Başla"
                            wire:click="$dispatch('slide-over.close')"
                            icon="tabler.shopping-cart"
                            class="btn-primary" />
                    </x-slot:actions>
                </x-empty-state>
            @else
                @auth
                    <div class="space-y-4">
                        @foreach ($cart as $c)
                            @php
                                $quantities = [];
                                $min = $c->item->buy_min ?? 0;
                                $max = $c->item->buy_max ?? 50;

                                for ($i = $min; $i <= $max; $i++) {
                                    $quantities[] = [
                                        'id' => $i,
                                        'name' => $i,
                                    ];
                                }
                            @endphp
                            
                            <div class="card bg-base-100 hover:shadow-md transition-all">
                                <div class="card-body p-4">
                                    <div class="flex items-start gap-4">
                                        <!-- Sol Taraf: Miktar ve Silme -->
                                        <div class="flex flex-col items-center gap-2">
                                            <x-select 
                                                wire:key="quantity-{{ $loop->index }}"
                                                wire:model.number.live="cart_array.{{ $loop->index }}.quantity"
                                                :options="$quantities"
                                                wire:change="changeQuantity({{ $c->id }}, $event.target.value)"
                                                class="select-sm w-20" />

                                            <x-button 
                                                icon="tabler.trash"
                                                wire:confirm="Bu ürünü sepetten kaldırmak istediğinize emin misiniz?"
                                                wire:click="deleteItem({{ $c->id }})"
                                                class="btn-sm btn-ghost text-error" />
                                        </div>

                                        <!-- Orta: Ürün Bilgileri -->
                                        <div class="flex-1">
                                            <h3 class="font-medium text-base">{{ $c->item->name }}</h3>
                                            <p class="text-base-content/70 text-sm">Birim Fiyat: @price($c->item->price)</p>
                                        </div>

                                        <!-- Sağ: Toplam Fiyat -->
                                        <div class="text-right">
                                            <p class="font-medium">@price($c->price * $c->quantity)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Özet -->
                    <div class="mt-6 p-4 rounded-lg bg-base-200 space-y-3">
                        <!-- Ara Toplam -->
                        <div class="flex justify-between items-center">
                            <span class="text-base-content/70">Ara Toplam</span>
                            <span class="font-medium">@price($subTotal)</span>
                        </div>

                        <!-- Vergi -->
                        <div class="flex justify-between items-center">
                            <span class="text-base-content/70">Vergi</span>
                            <span class="font-medium">@price($totalTax)</span>
                        </div>

                        <!-- Toplam -->
                        <div class="flex justify-between items-center pt-3 border-t border-base-300">
                            <span class="font-medium">Toplam</span>
                            <span class="text-lg font-semibold">@price($subTotal + $totalTax)</span>
                        </div>

                        <!-- Devam Et -->
                        <x-button 
                            label="Ödemeye Geç"
                            icon="tabler.arrow-right"
                            wire:click="checkout"
                            class="btn-primary w-full mt-4" />
                    </div>
                @endauth

                @guest
                    <div class="p-4 rounded-lg bg-primary/5 mt-6">
                        <div class="text-center space-y-3">
                            <x-icon name="tabler.lock" class="w-8 h-8 mx-auto text-primary" />
                            <h3 class="font-medium">Giriş Yapmanız Gerekiyor</h3>
                            <p class="text-sm text-base-content/70">Alışverişe devam etmek için lütfen giriş yapın.</p>
                            <x-button 
                                label="Giriş Yap"
                                icon="tabler.login"
                                wire:click="$dispatch('slide-over.open', {component: 'login.login-page'})"
                                class="btn-primary" />
                        </div>
                    </div>
                @endguest
            @endif
        </x-card>
    </div>
</div>
