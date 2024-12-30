<div>
    <!-- Başlık -->
    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
        <h1 class="text-lg font-semibold">Paketinizi Oluşturun</h1>
        <x-button icon="tabler.x" class="btn-sm btn-outline text-gray-600 ml-auto"
                  wire:click="$dispatch('slide-over.close')"/>
    </div>

    <div x-data="{ search: '', cart: @entangle('cart_array') }"
         class="rounded-lg p-4 space-y-4">
        <!-- Arama Alanı -->
        <x-input type="text"
                 class="input w-full input-bordered"
                 placeholder="Hizmet Ara..."
                 x-model="search"/>

        <!-- Hizmet Listesi -->
        <div class="divide-y divide-base-300">
            @foreach ($services as $service)
                <template x-if="{{ json_encode($service->name) }}.toLowerCase().includes(search.toLowerCase())">
                    <div class="flex items-center justify-between py-3">
                        <!-- Hizmet Bilgisi -->
                        <div>
                            <h3 class="font-medium text-base-content">{{ $service->name }}</h3>
                            <p class="text-sm text-base-content/70">@price($service->price)</p>
                        </div>

                        <!-- Miktar Kontrolleri -->
                        <div class="flex items-center space-x-4">
                            <!-- Azalt Butonu -->
                            <button class="btn btn-sm btn-outline btn-circle"
                                    @click="
                                    if (!cart['{{ $service->id }}']) {
                                        cart['{{ $service->id }}'] = { quantity: {{ $service->buy_min ?? 0 }} };
                                    }
                                    if (cart['{{ $service->id }}'].quantity > {{ $service->buy_min ?? 0 }}) {
                                        cart['{{ $service->id }}'].quantity = {{ $service->buy_min ?? 0 }};
                                    } else {
                                        cart['{{ $service->id }}'].quantity = 0;
                                    }
                                ">
                                <x-icon name="tabler.minus" class="w-5 h-5"/>
                            </button>

                            <!-- Miktar Gösterimi -->
                            <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                            <span x-text="cart['{{ $service->id }}']?.quantity ?? 0"></span>
                        </span>

                            <!-- Artır Butonu -->
                            <button class="btn btn-sm btn-outline btn-circle"
                                    @click="
                                    if (!cart['{{ $service->id }}']) {
                                        cart['{{ $service->id }}'] = { quantity: {{ $service->buy_min ?? 0 }} };
                                    } else if (cart['{{ $service->id }}'].quantity < {{ $service->buy_min ?? 0 }}) {
                                        cart['{{ $service->id }}'].quantity = {{ $service->buy_min ?? 0 }};
                                    } else {
                                        cart['{{ $service->id }}'].quantity = Math.min(
                                            cart['{{ $service->id }}'].quantity + 1,
                                            {{ $service->buy_max > 0 ? $service->buy_max : 'Infinity' }}
                                        );
                                    }
                                ">
                                <x-icon name="tabler.plus" class="w-5 h-5"/>
                            </button>
                        </div>
                    </div>
                </template>
            @endforeach
        </div>

        <!-- Sabit Alt Kısım -->
        <div class="pt-4">
            <x-button label="Sepete Git" wire:click="addToCart" class="btn btn-primary w-full py-2"/>
        </div>
    </div>


</div>
