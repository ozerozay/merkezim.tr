<div><x-button icon="tabler.shopping-bag" label="Sepetim ({{ $cartCount }})" class="btn-primary btn-lg"
        wire:key="cart-button-cvvvv" wire:click="$dispatch('slide-over.open', {component: 'web.shop.cart-page'})" />
</div>
