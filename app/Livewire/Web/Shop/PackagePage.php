<?php

namespace App\Livewire\Web\Shop;

use App\Managers\ShoppingCartManager;
use App\Models\ShopPackage;
use App\Traits\WebSettingsHandler;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Layout('components.layouts.client')]
#[Title('Online Mağaza')]
class PackagePage extends Component
{
    use Toast, WebSettingsHandler, WithPagination;

    #[Url]
    public string $search = '';

    #[Url(as: 'gender')]
    public int $currentGender = 1; // Varsayılan olarak erkek (2)

    #[Url]
    public string $sort = 'newest';

    public $sortOptions = [
        'price-asc' => 'Fiyat (Düşükten Yükseğe)',
        'price-desc' => 'Fiyat (Yüksekten Düşüğe)',
        'name-asc' => 'İsim (A-Z)',
        'name-desc' => 'İsim (Z-A)',
        'newest' => 'En Yeniler',
        'oldest' => 'En Eskiler',
    ];

    public function mount()
    {
        $this->currentGender = $this->currentGender ?: 2;
    }

    public function toggleGender()
    {
        $this->currentGender = $this->currentGender === 2 ? 1 : 2;
    }

    public function addToCart($package)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $package = ShopPackage::find($package);

        $product = [
            'id' => $package->id,
            'name' => $package->name,
            'price' => $package->price,
            'type' => 'package',
        ];

        (new ShoppingCartManager)->addItem($product, 1);

        $this->success('Sepete eklendi.');
        $this->dispatch('cart-item-added');
    }

    public function hasFilters(): bool
    {
        return !empty($this->search) || $this->sort !== 'newest';
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'sort']);
        $this->currentGender = 2;
    }

    public function getPackages()
    {
        return ShopPackage::query()
            ->where('active', true)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->currentGender, function ($query) {
                $query->whereHas('package', function ($q) {
                    $q->where('gender', $this->currentGender);
                });
            })
            ->when($this->sort, function ($query) {
                match ($this->sort) {
                    'price-asc' => $query->orderBy('price', 'asc'),
                    'price-desc' => $query->orderBy('price', 'desc'),
                    'name-asc' => $query->orderBy('name', 'asc'),
                    'name-desc' => $query->orderBy('name', 'desc'),
                    'newest' => $query->latest(),
                    'oldest' => $query->oldest(),
                    default => $query->latest()
                };
            })
            ->with(['package' => function ($q) {
                $q->with(['items.service.category']);
            }])
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.client.shop.package-page', [
            'packages' => $this->getPackages(),
            'hasFilters' => $this->hasFilters()
        ]);
    }
}
