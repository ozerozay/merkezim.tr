<?php

namespace App\Livewire\Web\Shop;

use App\Managers\ShoppingCartManager;
use App\Models\ShopPackage;
use App\Traits\WebSettingsHandler;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('components.layouts.client')]
#[Title('Online Mağaza')]
class PackagePage extends Component
{
    use Toast, WebSettingsHandler;

    #[Url]
    public string $search = '';

    #[Url(as: 'gender')]
    public array $gender_id = [];

    #[Url]
    public array $categories_id = [];

    public function mount()
    {
        $this->getSettings();
    }

    public function addToCart($package)
    {

        $package = ShopPackage::find($package);

        $product = [
            'id' => $package->id,
            'name' => $package->name,
            'price' => $package->price,
            'type' => 'package', // 'type' burada 'product' olarak belirtilir
        ];

        (new ShoppingCartManager)->addItem($product, 1);

        $this->success('Sepete eklendi.');

        $this->dispatch('cart-item-added');
    }

    public function hasFilters(): bool
    {
        return count($this->gender_id) || count($this->categories_id) || $this->search;
    }

    public function clearFilters(): void
    {
        $this->reset();
    }

    public function getPackages()
    {
        return ShopPackage::query()
            ->where('active', true)
            ->with('package.items.service.category')
            ->get();
    }

    public function render()
    {
        return view('livewire.client.shop.package-page', [
            'genders' => [
                [
                    'id' => 1,
                    'name' => 'KADIN',
                ],
                [
                    'id' => 2,
                    'name' => 'ERKEK',
                ],
            ],
            'categories' => [],
            'hasFilters' => $this->hasFilters(),
            'packages' => $this->getPackages(),
        ]);
    }
}
