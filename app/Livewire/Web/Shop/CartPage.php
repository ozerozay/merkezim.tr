<?php

namespace App\Livewire\Web\Shop;

use App\Enum\PaymentType;
use App\Managers\ShoppingCartManager;
use App\Models\CartItem;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CartPage extends SlideOver
{
    use Toast;

    public $cart = [];

    public $cart_array = [];

    public $listeners = [
        'client-logged-in' => '$refresh',
    ];

    public function deleteItem($id)
    {
        CartItem::where('id', $id)->delete();
        $this->cart = [];
        $this->cart_array = [];
        $this->updateCart();
        $this->dispatch('cart-item-added');
    }

    public function changeQuantity($id, $quantity)
    {
        switch ($quantity) {
            case 0:
                CartItem::where('id', $id)->delete();
                break;
            default:
                $item = CartItem::where('id', $id)->first();
                if ($item->item->buy_max) {
                    if ($item->item->buy_max < $quantity) {
                        $this->error('En fazla '.$item->item->buy_max.' adet alabilirsiniz.');
                        $this->updateCart();

                        return;
                    }
                }
                if ($item->item->buy_min) {
                    if ($item->item->buy_min > $quantity) {
                        $this->error('En az '.$item->item->buy_min.' adet alabilirsiniz.');
                        $this->updateCart();

                        return;
                    }
                }
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
        $this->cart = $manager->getCart()->items;
        $this->cart_array = collect($this->cart)->toArray();
    }

    public function checkout()
    {
        $manager = new ShoppingCartManager;
        $arguments = [
            'type' => PaymentType::cart->name ?? null,
            'data' => [
                'cart_id' => $manager->getCart()->id,
                'amount' => $manager->getSubTotal(),
            ],
        ];

        $this->dispatch('slide-over.open', component: 'web.shop.checkout-page', arguments: $arguments);
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
