<?php

use App\Actions\Client\GetClientByUniqueID;
use App\Actions\Product\GetProductsAction;
use App\Models\Product;
use App\Traits\LiveHelper;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    #[Modelable]
    public ?Collection $selected_products;

    public $branch_ids = [];

    public $client = null;

    public $client_model = null;

    public $product_collection;

    public function mount()
    {
        LiveHelper::class;

        $this->init();
    }

    public function init()
    {
        $this->client_model = GetClientByUniqueID::run(null, $this->client);
        if ($this->client_model) {
            $this->branch_ids = [$this->client_model->branch_id];

            $this->updateProducts();
        }

    }

    public function updateProducts()
    {
        $this->product_collection = GetProductsAction::run($this->branch_ids);
        //dump($this->product_collection);
        $this->dispatch('card-product-add-product-update-collection', $this->product_collection)->to('components.card.product.card_product_add_product');
    }

    #[Computed]
    public function totalPrice()
    {
        return $this->selected_products->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function totalPriceWithoutGift()
    {
        return $this->selected_products->sum(function ($item) {
            if (! $item['gift']) {
                return $item['price'] * $item['quantity'];
            }
        });
    }

    #[On('card-product-added')]
    public function addProduct($product)
    {
        $products = Product::select(['id', 'name', 'stok', 'price'])->whereIn('id', $product['product_id'])->get();

        foreach ($products as $p) {
            if ($p->stok < $product['quantity']) {
                $this->error($p->name.' ürününden '.$p->stok.' adet bulunuyor.');
                break;
            }

            if ($this->selected_products->count() > 0 && ($product['gift'] != true) && $this->selected_products->contains(function ($q) use ($p) {
                return $q['product_id'] == $p->id;
            })) {
                $this->error($p->name.' bulunuyor, tablodan değişiklik yapın.');
                break;
            }

            $lastId = $this->selected_products->last() != null ? $this->selected_products->last()['id'] + 1 : 1;

            $this->selected_products->push([
                'id' => $lastId,
                'product_id' => $p->id,
                'name' => $p->name,
                'gift' => $product['gift'],
                'quantity' => $product['quantity'],
                'price' => $product['quantity'] * $p->price,
            ]);
        }

        $this->dispatchSelectedProducts();
    }

    public function deleteItem($id)
    {
        $this->selected_products = $this->selected_products->reject(function ($item) use ($id) {
            return $item['id'] == $id;
        });
        $this->dispatchSelectedProducts();
    }

    public function deleteProducts()
    {
        $this->selected_products = collect();
        $this->dispatchSelectedProducts();
    }

    public function dispatchSelectedProducts()
    {
        $this->dispatch('card-product-selected-products-updated', $this->selected_products);
    }

    #[On('card-product-client-selected')]
    public function dispatchClientSelected($client)
    {
        $this->client = $client;
        $this->client_model = null;
        $this->init();
    }
};
?>
<div>
    <x-card title="Ürün" separator progress-indicator>
        @foreach ($selected_products as $product)
        <x-list-item :item="$product" no-separator no-hover>
            @if ($product['gift'])
            <x-slot:avatar>
                <x-badge value="H" class="badge-primary" />
            </x-slot:avatar>
            @endif
            <x-slot:value>
                {{ $product['name'] }}
            </x-slot:value>
            <x-slot:sub-value>
                {{ $product['quantity'] }} adet - {{ LiveHelper::price_text($product['price']) }}
            </x-slot:sub-value>
            <x-slot:actions>
                <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                    wire:click="deleteItem({{ $product['id'] }}, 'service')" spinner />
            </x-slot:actions>
        </x-list-item>
        @endforeach
        <x:slot:menu>
            <livewire:components.card.product.card_product_add_product :product_collection="$product_collection" />
            @if ($selected_products->count() > 0)
            <x-button icon="o-trash" class="text-red-500 btn-sm" wire:confirm="Emin misiniz ?"
                wire:click="deleteProducts()" spinner />
            @endif
        </x:slot:menu>
        <x:slot:actions>
            Toplam : {{ LiveHelper::price_text($this->totalPriceWithoutGift()) }}
        </x:slot:actions>
    </x-card>
</div>