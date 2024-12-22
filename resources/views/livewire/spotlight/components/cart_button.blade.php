<?php

new class extends Livewire\Volt\Component {
    public $cartCount = 0; // Sepetteki ürün sayısı

    #[Livewire\Attributes\On('add-cart-item')]
    public function addItemToCart()
    {
        $this->cartCount++;
    }

    public function aa()
    {
        dump('loe');
    }
};

?>
<div class="fixed bottom-4 left-1/2 transform -translate-x-1/2" wire:key="cart-buttson--{{Str::random(10)}}">
    <x-button icon="tabler.shopping-bag" wire:click="$dispatch('slide-over.open', {component: 'client.shop.cart-page'})"
              label="Sepetim ({{ $cartCount }})" class="btn-primary btn-lg animate-pulse"
              wire:key="cart-button-{{Str::random(10)}}"
              wire:transition.duration.500ms/>
</div>
