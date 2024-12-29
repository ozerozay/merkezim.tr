<div>
    <div class=" bg-base-200">
        <!-- Başlık -->
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h1 class="text-lg font-semibold">Paketinizi Oluşturun</h1>
            <x-button icon="tabler.x" class="btn-sm btn-outline text-gray-600 ml-auto"
                      wire:click="$dispatch('slide-over.close')"/>
        </div>
        <div class="flex-1 overflow-y-auto p-4 space-y-4 ">

            @foreach ($services as $service)
                @php
                    $quantities = [];
                    $max = $service->buy_max ?? 50;

                    for ($i = 0; $i <= $max; $i++) {
                        $quantities[] = [
                            'id' => $i,
                            'name' => $i,
                        ];
                    }
                @endphp
                <x-list-item :item="$service" wire:key="itm-{{ $service->id }}">
                    <x-slot:actions>
                        <x-select wire:key="dfdsf-{{ $loop->index }}"
                                  wire:model.number.live="cart_array.{{ $service->id }}.quantity" :options="$quantities"
                                  class="select-sm"/>
                    </x-slot:actions>
                    <x-slot:value>
                        {{ $service->name }}
                    </x-slot:value>
                    <x-slot:sub-value>
                        @price($service->price)
                    </x-slot:sub-value>
                </x-list-item>
            @endforeach

        </div>
        <!-- Sabit Alt Kısım -->
        <div class="p-4 border-t border-gray-200 bg-base-200">

            <x-button label="Sepete Ekle" wire:click="addToCart" class="btn btn-primary w-full py-2 mt-4"/>

        </div>
    </div>


</div>
