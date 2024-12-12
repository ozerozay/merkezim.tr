<?php

namespace App\Livewire\Web\Shop;

use App\Managers\ShoppingCartManager;
use App\Models\ShopService;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreatePackage extends SlideOver
{
    use Toast;

    public $service_ids = [];

    public $seans = 1;

    public $cart_array = [];

    public function addToCart()
    {

        $cart_array = collect($this->cart_array);

        if ($cart_array->isEmpty()) {
            $this->error('Hizmet seçmelisiniz.');

            return;
        }

        foreach ($cart_array as $key => $value) {

            $service = ShopService::find($key);

            $service = [
                'id' => $service->id,
                'name' => $service->name,
                'price' => $service->price,
                'type' => 'service',
            ];

            (new ShoppingCartManager)->addItem($service, $value['quantity']);

            $this->success('Sepete eklendi.');
            $this->dispatch('cart-item-added');
        }
    }

    public function getServices()
    {
        return ShopService::query()
            ->where('active', true)
            ->orderBy('name')
            ->whereHas('service', function ($q) {
                $q->whereIn('gender', [0, auth()->user()->gender]);
            })
            ->where('branch_id', auth()->user()->branch_id)
            ->with('service.category')
            ->get();
    }

    public function render()
    {
        return view('livewire.client.shop.create-package', [
            'services' => $this->getServices(),
        ]);
    }
}
