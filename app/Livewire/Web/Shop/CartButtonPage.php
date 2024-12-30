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

    #[On('card-add-item')]
    public function incrementItemPackage($package): void
    {

        $this->shoppingCartManager = new ShoppingCartManager;

        // Sepete ürün ekleme
        $product = [
            'id' => $package->id,
            'name' => $package->name,
            'price' => $package->price,
            'type' => 'package', // 'type' burada 'product' olarak belirtilir
        ];
        $quantity = $this->shoppingCartManager->addItem(
            $product,
            1
        );

        $this->cartQuantity = $quantity;
    }

    public function decrementItemPackage(): void
    {
        if ($this->cartQuantity > 0) {
            $this->cartQuantity--;

            // Sepetten ürün çıkarma
            $quantity = this->shoppingCartManager->addItem(
                ['type' => 'package', 'id' => $this->package->id],
                -1
            );
            $this->cartQuantity = $quantity;
        } else {
            //$this->shoppingCartManager
        }
    }

    public function render()
    {
        return view('livewire.client.shop.cart-button-page');
    }
}
