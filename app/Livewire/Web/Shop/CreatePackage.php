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

    public function addToCart(): void
    {
        $cart_array = collect($this->cart_array);

        if ($cart_array->isEmpty()) {
            $this->error('Hizmet seçmelisiniz.');

            return;
        }

        foreach ($cart_array as $key => $value) {
            $service = ShopService::find($key);

            if (! $service) {
                $this->error('Hizmet bulunamadı.');

                continue;
            }

            $quantity = $value['quantity'] ?? 0;

            // Quantity kontrolü
            if ($quantity > 0) {
                if (isset($service->buy_min) && $quantity < $service->buy_min) {
                    $this->error("{$service->name} için minimum adet: {$service->buy_min}");

                    return;
                }

                if (isset($service->buy_max) && $service->buy_max > 0 && $quantity > $service->buy_max) {
                    $this->error("{$service->name} için maksimum adet: {$service->buy_max}");

                    return;
                }

                // Sepete ekle
                $item = [
                    'id' => $service->id,
                    'name' => $service->name,
                    'price' => $service->price,
                    'type' => 'service',
                ];

                (new ShoppingCartManager)->addItem($item, $quantity);
            }
        }

        $this->success('Sepete eklendi.');
        $this->dispatch('cart-item-added');
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

    public static function behavior(): array
    {
        return [
            // Close the slide-over if the escape key is pressed
            'close-on-escape' => true,
            // Close the slide-over if someone clicks outside the slide-over
            'close-on-backdrop-click' => true,
            // Trap the users focus inside the slide-over (e.g. input autofocus and going back and forth between input fields)
            'trap-focus' => true,
            // Remove all unsaved changes once someone closes the slide-over
            'remove-state-on-close' => false,
        ];
    }

    public function render()
    {
        return view('livewire.client.shop.create-package', [
            'services' => $this->getServices(),
        ]);
    }
}
