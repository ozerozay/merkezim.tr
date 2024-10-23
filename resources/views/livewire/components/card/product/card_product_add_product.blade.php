<?php

use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public bool $show = false;

    public $product_id = [];

    public $gift = false;

    public int $quantity = 1;

    public $product_collection;

    #[On('card-product-add-product-update-collection')]
    public function updateProductCollection($product_collection)
    {
        dump($product_collection);
        $this->product_collection = $product_collection;
    }

    public function addProduct()
    {
        $validator = Validator::make([
            'product_id' => $this->product_id,
            'gift' => $this->gift,
            'quantity' => $this->quantity,
        ], [
            'product_id' => 'required|array',
            'gift' => 'required|boolean',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->dispatch('card-product-added', $validator->validated());

        $this->reset('product_id', 'gift');
    }
};

?>
<div>
    <x-button label="Ürün" icon="o-plus" @click="$wire.show = true" spinner class="btn-sm btn-outline" />
    <x-modal wire:model="show" title="Ürün">
        <x-form wire:submit="addProduct">
            <x-choices-offline label="Ürün" wire:model="product_id" :options="$product_collection"
                option-sub-label="stok" option-avatar="cover" icon="o-magnifying-glass" hint="Ürün Ara"
                no-result-text="Ürün bulunamadı."
                searchable
                 />
            <livewire:components.form.number_dropdown label="Adet" wire:model="quantity" />
            <x-checkbox label="Hediye" wire:model="gift" />
            <x-slot:actions>
                <x-button label="İptal" @click="$wire.show = false" />
                <x-button label="Ekle" type="submit" spinner="addProduct" icon="o-paper-airplane"
                    class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>