<?php

namespace App\Livewire\Client\Shop;

use App\Managers\ShoppingCartManager;
use Livewire\Component;

class CartDropdown extends Component
{
    public $cart = [];
    public $subTotal = 0;

    protected $listeners = ['cart-item-added' => 'updateCart'];

    public function mount()
    {
        $this->updateCart();
    }

    public function updateCart()
    {
        $manager = new ShoppingCartManager;
        $this->cart = $manager->getCart()->items;
        $this->subTotal = $manager->getSubTotal();
    }

    public function changeQuantity($id, $quantity)
    {
        $this->dispatch('change-quantity', id: $id, quantity: $quantity);
        $this->updateCart();
    }

    public function deleteItem($id)
    {
        $this->dispatch('delete-item', id: $id);
        $this->updateCart();
    }

    public function checkout()
    {
        $this->dispatch('slide-over.open', component: 'web.shop.cart-page');
    }

    public function render()
    {
        return view('livewire.client.shop.cart-dropdown');
    }
}
