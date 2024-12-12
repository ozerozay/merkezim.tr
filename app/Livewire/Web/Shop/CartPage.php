<?php

namespace App\Livewire\Web\Shop;

use App\Managers\ShoppingCartManager;
use App\Models\CartItem;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CartPage extends SlideOver
{
    public $cart = [];

    public $cart_array = [];

    public $listeners = [
        'client-logged-in' => '$refresh',
    ];

    public function changeQuantity($id, $quantity)
    {

        switch ($quantity) {
            case 0:
                CartItem::where('id', $id)->delete();
                break;
            default:
                CartItem::where('id', $id)->update(['quantity' => $quantity]);
                break;
        }
        $this->cart = [];
        $this->cart_array = [];
        $this->updateCart();
        $this->dispatch('cart-item-added');
    }

    public function mount()
    {
        $this->updateCart();

    }

    public function updateCart()
    {
        $manager = new ShoppingCartManager;
        $this->cart = [];
        $this->cart_array = [];
        $this->cart = $manager->getCart();
        $this->cart_array = collect($this->cart)->toArray();
    }

    public function checkout()
    {

        // $this->dispatch('slide-over.open', ['component' => 'web.shop.checkout-page']);
    }

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function render()
    {
        $manager = new ShoppingCartManager;

        return view('livewire.client.shop.cart-page', [
            'cart' => $this->cart,
            'subTotal' => $manager->getSubTotal(),
            'totalTax' => $manager->getTotalTax(),
        ]);
    }
}
