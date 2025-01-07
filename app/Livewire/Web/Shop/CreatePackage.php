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
    public $search = '';

    protected $listeners = [
        'cart-item-added' => '$refresh'
    ];

    public function addToCart(): void
    {
        try {
            $cart_array = collect($this->cart_array);

            if ($cart_array->isEmpty()) {
                throw new \Exception('Lütfen en az bir hizmet seçin.');
            }

            foreach ($cart_array as $key => $value) {
                $service = ShopService::find($key);

                if (!$service) {
                    throw new \Exception('Hizmet bulunamadı.');
                }

                $quantity = $value['quantity'] ?? 0;

                // Miktar doğrulaması
                $this->validateQuantity($service, $quantity);

                // Sepete ekle
                $this->addItemToCart($service, $quantity);
            }

            $this->success('Hizmetler sepete eklendi.');
            $this->dispatch('cart-item-added');
            $this->close();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    protected function validateQuantity($service, $quantity): void
    {
        if ($quantity <= 0) {
            return;
        }

        if (isset($service->buy_min) && $quantity < $service->buy_min) {
            throw new \Exception("{$service->name} için minimum adet: {$service->buy_min}");
        }

        if (isset($service->buy_max) && $service->buy_max > 0 && $quantity > $service->buy_max) {
            throw new \Exception("{$service->name} için maksimum adet: {$service->buy_max}");
        }
    }

    protected function addItemToCart($service, $quantity): void
    {
        if ($quantity <= 0) {
            return;
        }

        $item = [
            'id' => $service->id,
            'name' => $service->name,
            'price' => $service->price,
            'type' => 'service',
        ];

        (new ShoppingCartManager)->addItem($item, $quantity);
    }

    public function getServices()
    {
        return ShopService::query()
            ->where('active', true)
            ->orderBy('name')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->whereHas('service', function ($q) {
                $q->whereIn('gender', [0, auth()->user()->gender]);
            })
            ->where('branch_id', auth()->user()->branch_id)
            ->with(['service.category'])
            ->get();
    }

    public function clearCart(): void
    {
        $this->cart_array = [];
        $this->success('Seçimler temizlendi.');
    }

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
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
