<div>
    <div class="overflow-x-hidden">
        <x-card title="Sepetim" subtitle="asd" separator progress-indicator>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')"/>
            </x-slot:menu>
            @if ($cart->isEmpty())
                <div class="text-center text-gray-500 py-4">
                    <p class="text-lg">Sepetiniz boş!</p>
                    <x-button label="Alışverişe Başla" class="mt-4 btn-primary"/>
                </div>
            @else
                @auth
                    @foreach ($cart as $c)
                        @php
                            $quantities = [];
                            $max = $c->item->buy_max ?? 50;

                            for ($i = 0; $i <= $max; $i++) {
                                $quantities[] = [
                                    'id' => $i,
                                    'name' => $i,
                                ];
                            }
                        @endphp
                        <x-list-item :item="$c" wire:key="itm-{{ $c->id }}">
                            <x-slot:avatar>

                                <div class="flex justify-start mb-4">
                                    <x-select wire:key="dfdsf-{{ $loop->index }}"
                                              wire:model.number.live="cart_array.{{ $loop->index }}.quantity"
                                              :options="$quantities"
                                              wire:change="changeQuantity({{ $c->id }}, $event.target.value)"
                                              class="select-sm"/>
                                </div>
                                <div class="flex justify-start mt-4">
                                    <x-button class="btn btn-sm btn-outline btn-error"
                                              wire:confirm="Emin misiniz ?"
                                              wire:click="deleteItem({{ $c->id }})"> SİL
                                    </x-button>
                                </div>
                            </x-slot:avatar>
                            <x-slot:value>
                                {{ $c->item->name }}
                            </x-slot:value>
                            <x-slot:sub-value>
                                @price($c->item->price)
                            </x-slot:sub-value>
                            <x-slot:actions>
                                <div class="flex flex-col">
                                    @price($c->price * $c->quantity)
                                </div>
                            </x-slot:actions>
                        </x-list-item>
                    @endforeach
                @endauth

                @guest
                    @foreach ($cart as $c)
                        @php
                            $quantities = [];
                            $max = $c->item->buy_max ?? 50;

                            for ($i = 0; $i <= $max; $i++) {
                                $quantities[] = [
                                    'id' => $i,
                                    'name' => $i,
                                ];
                            }
                        @endphp
                        <x-list-item :item="$c" wire:key="itm-{{ $c['id'] }}">
                            <x-slot:avatar>
                                <x-badge value="{{ $c['quantity'] }}"/>
                            </x-slot:avatar>
                            <x-slot:value>
                                {{ $c['name'] }}
                            </x-slot:value>
                            <x-slot:sub-value>
                                @price($c['price'])
                            </x-slot:sub-value>
                            <x-slot:actions>
                                <div class="flex flex-col">
                                    @price($c['name'] * $c['price'])
                                </div>
                            </x-slot:actions>
                        </x-list-item>
                    @endforeach
                @endguest

            @endif
            <div class="p-4 border-t border-gray-200 bg-base-200 box-border">
                @auth
                    <div class="flex justify-between text-sm">
                        <p>Ara Toplam</p>
                        <p class="font-medium">@price($subTotal)</p>
                    </div>
                    <div class="flex justify-between text-sm">
                        <p>Vergi</p>
                        <p class="font-medium">@price($totalTax)</p>
                    </div>
                    <div class="flex justify-between text-sm font-bold">
                        <p>Toplam</p>
                        <p>@price($subTotal + $totalTax)</p>
                    </div>
                    <x-button label="Devam"
                              wire:click="$dispatch('slide-over.open',
                {component: 'web.shop.checkout-page' })"
                              class="btn btn-primary w-full py-2 mt-4"/>
                @endauth
                @guest
                    <x-button label="Devam etmek için giriş yapın"
                              wire:click="$dispatch('slide-over.open',
                {component: 'login.login-page'})"
                              class="btn btn-primary w-full py-2 mt-4"/>
                @endguest

            </div>
        </x-card>
    </div>


</div>
