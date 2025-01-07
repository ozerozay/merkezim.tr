<div class="dropdown dropdown-end" x-data>
    <label tabindex="0" class="btn btn-ghost btn-circle">
        <div class="indicator">
            <x-icon name="tabler.shopping-cart" class="h-5 w-5"/>
            @if(count($cart) > 0)
                <span class="badge badge-sm badge-primary indicator-item">{{ count($cart) }}</span>
            @endif
        </div>
    </label>
    
    <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-80 bg-base-100 shadow-xl">
        <div class="card-body">
            @if(count($cart) > 0)
                <span class="font-bold text-lg">{{ count($cart) }} Ürün</span>
                <div class="max-h-96 overflow-y-auto divide-y">
                    @foreach($cart as $item)
                        <div class="flex items-center gap-2 py-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ $item['name'] }}</p>
                                <p class="text-xs text-base-content/70">
                                    @price($item['price']) x {{ $item['quantity'] }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1">
                                <button 
                                    class="btn btn-xs btn-ghost"
                                    wire:click="changeQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                >
                                    <x-icon name="tabler.minus" class="w-3 h-3"/>
                                </button>
                                <span class="text-sm font-medium w-4 text-center">{{ $item['quantity'] }}</span>
                                <button 
                                    class="btn btn-xs btn-ghost"
                                    wire:click="changeQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                >
                                    <x-icon name="tabler.plus" class="w-3 h-3"/>
                                </button>
                                <button 
                                    class="btn btn-xs btn-ghost text-error"
                                    wire:click="deleteItem({{ $item['id'] }})"
                                >
                                    <x-icon name="tabler.trash" class="w-3 h-3"/>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="border-t pt-4">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Toplam</span>
                        <span class="font-bold">@price($subTotal)</span>
                    </div>
                    <button 
                        class="btn btn-primary btn-block btn-sm"
                        wire:click="checkout"
                    >
                        Sepeti Görüntüle
                    </button>
                </div>
            @else
                <div class="py-8 text-center">
                    <x-icon name="tabler.shopping-cart-off" class="w-12 h-12 mx-auto text-base-content/30"/>
                    <p class="mt-2 text-sm text-base-content/70">Sepetiniz boş</p>
                </div>
            @endif
        </div>
    </div>
</div> 