<div>
    <div class="overflow-x-hidden">
        <x-card title="🛒 Sepetim" subtitle="Hizmetlerinizi düzenleyin" separator progress-indicator>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')"/>
            </x-slot:menu>
            @if ($cart->isEmpty())
                <div class="text-center text-gray-500 py-4">
                    <p class="text-lg">🚨 Sepetiniz boş!</p>
                    <x-button wire:click="$dispatch('slide-over.close')" label="Alışverişe Başla"
                              class="mt-4 btn-primary"/>
                </div>
            @else
                @auth
                    @foreach ($cart as $c)
                        @php
                            $quantities = [];
                            $min = $c->item->buy_min ?? 0; // Minimum değer
                            $max = $c->item->buy_max ?? 50; // Maksimum değer

                            for ($i = $min; $i <= $max; $i++) {
                                $quantities[] = [
                                    'id' => $i,
                                    'name' => $i,
                                ];
                            }
                        @endphp
                        <x-list-item :item="$c" wire:key="itm-{{ $c->id }}">
                            <x-slot:avatar>
                                <div class="flex flex-col items-center space-y-2">
                                    <!-- Dropdown -->
                                    <x-select wire:key="quantity-{{ $loop->index }}"
                                              wire:model.number.live="cart_array.{{ $loop->index }}.quantity"
                                              :options="$quantities"
                                              wire:change="changeQuantity({{ $c->id }}, $event.target.value)"
                                              class="select-sm"/>

                                    <!-- Sil Butonu -->
                                    <x-button class="btn btn-sm btn-outline btn-error flex items-center space-x-2"
                                              wire:confirm="Emin misiniz?"
                                              wire:click="deleteItem({{ $c->id }})">SİL
                                    </x-button>
                                </div>
                            </x-slot:avatar>
                            <x-slot:value>
                                <p class="break-words whitespace-normal text-base-content">
                                    {{ $c->item->name }}
                                </p>
                            </x-slot:value>
                            <x-slot:sub-value>
                                💵 @price($c->item->price)
                            </x-slot:sub-value>
                            <x-slot:actions>
                                <div class="flex flex-col text-right">
                                    🧾 @price($c->price * $c->quantity)
                                </div>
                            </x-slot:actions>
                        </x-list-item>
                    @endforeach
                @endauth

                <!-- Alt Bölüm -->
                <div class="p-4 border-t border-gray-200 bg-base-200 box-border">
                    @auth
                        <!-- Ara Toplam -->
                        <div class="flex justify-between text-sm">
                            <p>🛒 Ara Toplam</p>
                            <p class="font-medium">@price($subTotal)</p>
                        </div>
                        <!-- Vergi -->
                        <div class="flex justify-between text-sm">
                            <p>📊 Vergi</p>
                            <p class="font-medium">@price($totalTax)</p>
                        </div>
                        <!-- Toplam -->
                        <div class="flex justify-between text-sm font-bold">
                            <p>💳 Toplam</p>
                            <p>@price($subTotal + $totalTax)</p>
                        </div>
                        <!-- Devam Butonu -->
                        <x-button label="➡️ Devam Et"
                                  wire:click="checkout"
                                  class="btn btn-primary w-full py-2 mt-4"/>
                    @endauth
                    @guest
                        <!-- Misafir Devam Butonu -->
                        <x-button label="🔑 Devam etmek için giriş yapın"
                                  wire:click="$dispatch('slide-over.open',
                {component: 'login.login-page'})"
                                  class="btn btn-primary w-full py-2 mt-4"/>
                    @endguest
                </div>
            @endif
        </x-card>
    </div>
</div>
