<?php

use App\Models\Product;
use App\Traits\ResetsPaginationWhenPropsChanges;
use App\Traits\LiveHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new
    #[Title('Ürün')]
    class extends Component {

        use Toast, WithPagination, ResetsPaginationWhenPropsChanges;

        #[Url()]
        public String $search = '';

        #[Url]
        public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

        public ?Product $service;

        #[On('product-saved')]
        #[On('product-cancel')]
        public function clear(): void
        {
            $this->reset();
        }

        public function products(): LengthAwarePaginator
        {
            return Product::query()
                ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%$this->search%"))
                ->orderBy(...array_values($this->sortBy))
                ->with('branch')
                ->paginate(9);
        }

        public function edit(Product $product): void
        {
            $this->product = $product;
        }

        public function headers(): array
        {
            return [
                ['key' => 'id', 'label' => '#', 'class' => 'w-20'],
                ['key' => 'name', 'label' => 'Ad'],
                ['key' => 'branch.name', 'label' => 'Şube', 'sortable' => false],
                ['key' => 'price', 'label' => 'Fiyat'],
                ['key' => 'stok', 'label' => 'Stok'],
            ];
        }

        public function with(): array
        {
            return [
                'products' => $this->products(),
                'headers' => $this->headers(),
            ];
        }
    };
?>

<div>
    <x-header title="Ürün" seperator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Ara..." wire:model.live.debounce="search" icon="o-magnifying-glass" clearable />
        </x-slot:middle>
        <x-slot:actions>
            <livewire:admin.settings.defination.product.create />

        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$headers" :rows="$products" @row-click="$wire.edit($event.detail.id)" :sort-by="$sortBy"
            with-pagination>
            @scope('actions', $product)
            <x-badge value="{{ $product->active ? 'Aktif' : 'Pasif' }}"
                class="{{ $product->active ? 'badge-primary' : 'badge-error' }}" />
            @endscope
            @scope('cell_price', $service)
            {{ LiveHelper::price_text($service->price) }}
            @endscope
        </x-table>
    </x-card>
    <livewire:admin.settings.defination.product.edit wire:model="product" />

</div>