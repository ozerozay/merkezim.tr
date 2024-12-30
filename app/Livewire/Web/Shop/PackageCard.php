<?php

namespace App\Livewire\Web\Shop;

use App\Managers\ShoppingCartManager;
use App\Models\ShopPackage;
use Livewire\Component;

class PackageCard extends Component
{
    public ?ShopPackage $package;

    public $cartQuantity = 0;

    protected ?ShoppingCartManager $shoppingCartManager;

    public function mount(): void
    {
        $this->shoppingCartManager = new ShoppingCartManager;

        $cart = $this->shoppingCartManager->getCart();
        //dump($cart);
        $this->cartQuantity = $cart->items()->where('item_id', $this->package->id)
            ->where('item_type', 'App\Models\ShopPackage')->first()['quantity'] ?? 0;
    }

    public function incrementItem(): void
    {
        $this->shoppingCartManager = new ShoppingCartManager;

        $product = [
            'id' => $this->package->id,
            'name' => $this->package->name,
            'price' => $this->package->price,
            'type' => 'package', // 'type' burada 'product' olarak belirtilir
        ];

        $quantity = $this->shoppingCartManager->addItem(
            $product,
            1
        );
        $this->cartQuantity = $quantity;
        $this->dispatch('cart-item-added');
    }

    public function decrementItem(): void
    {
        $quantity = (new ShoppingCartManager)->addItem(
            ['type' => 'package', 'id' => $this->package->id],
            -1
        );
        $this->cartQuantity = $quantity;
        $this->dispatch('cart-item-added');
    }

    public function render()
    {
        return view('livewire.client.shop.package-card');
    }
}
