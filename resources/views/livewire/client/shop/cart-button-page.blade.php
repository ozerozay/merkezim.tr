<div>
    <x-button 
        wire:key="cart-button-{{ rand() }}" 
        wire:click="$dispatch('slide-over.open', {component: 'web.shop.cart-page'})"
        class="btn-primary relative">
        
        <!-- İkon ve Etiket -->
        <div class="flex items-center gap-2">
            <x-icon name="tabler.shopping-cart" class="w-5 h-5" />
            <span>Sepetim</span>
        </div>

        <!-- Ürün Sayısı Badge -->
        @if($cartCount > 0)
            <div class="absolute -top-2 -right-2">
                <span class="badge badge-sm badge-accent text-white">
                    {{ $cartCount }}
                </span>
            </div>
        @endif
    </x-button>
</div>
