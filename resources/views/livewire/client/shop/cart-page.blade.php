<div>
    <div class="fixed inset-y-0 right-0 w-full max-w-md shadow-lg flex flex-col h-screen  bg-base-200">
        <!-- Başlık -->
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h1 class="text-lg font-semibold">Sepetim</h1>
            <x-button icon="tabler.x" class="btn-sm btn-outline text-gray-600 ml-auto"
                wire:click="$dispatch('slide-over.close')" />
        </div>
        <div class="flex-1 overflow-y-auto p-4 space-y-4 ">
            @php
                $quantities = [];

                for ($i = 0; $i <= 99; $i++) {
                    $quantities[] = [
                        'id' => $i,
                        'name' => $i,
                    ];
                }
            @endphp
            @if ($cart->isEmpty())
                <div class="text-center text-gray-500 py-4">
                    <p class="text-lg">Sepetiniz boş!</p>
                    <x-button label="Alışverişe Başla" class="mt-4 btn-primary" />
                </div>
            @else
                @auth
                    @foreach ($cart as $c)
                        <x-list-item :item="$c" wire:key="itm-{{ $c->id }}">
                            <x-slot:avatar>

                                <x-select wire:key="dfdsf-{{ $loop->index }}"
                                    wire:model.number.live="cart_array.{{ $loop->index }}.quantity" :options="$quantities"
                                    wire:change="changeQuantity({{ $c->id }}, $event.target.value)"
                                    class="select-sm" />
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
                        <x-list-item :item="$c" wire:key="itm-{{ $c['id'] }}">
                            <x-slot:avatar>
                                <x-badge value="{{ $c['quantity'] }}" />
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


        </div>
        <!-- Sabit Alt Kısım -->
        <div class="p-4 border-t border-gray-200 bg-base-200">
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
                    class="btn btn-primary w-full py-2 mt-4" />
            @endauth
            @guest
                <x-button label="Devam etmek için giriş yapın"
                    wire:click="$dispatch('slide-over.open',
                {component: 'login.login-page'})"
                    class="btn btn-primary w-full py-2 mt-4" />
            @endguest

        </div>
    </div>


</div>
