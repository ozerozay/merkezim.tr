<?php

namespace App\Livewire\Web\Shop;

use App\Managers\ShoppingCartManager;
use Livewire\Attributes\On;
use Livewire\Component;

class CartButtonPage extends Component
{
    public $cartCount = 0; // Sepetteki ürün sayısı

    public function mount()
    {
        $this->cartCount = (new ShoppingCartManager)->getCartCount();
    }

    #[On('cart-item-added')]
    public function updateCartCount()
    {
        $this->cartCount = (new ShoppingCartManager)->getCartCount();
    }

    public function render()
    {
        return view('livewire.client.shop.cart-button-page');
    }
}
