<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $products = [];

    public $product_collection;

    public function mount()
    {
        $this->product_collection = \App\Models\Product::where('active', true)
            ->whereHas('branch', function ($q) {
                $q->whereIn('id', auth()->user()->staff_branches);
            })
            ->get();
    }
};
?>

<div>
    <x-choices-offline wire:key="scsesde-{{ Str::random(20) }}" wire:model="products" :options="$product_collection"
        option-sub-label="stok" label="Ürün" icon="o-magnifying-glass" searchable="true" />
</div>
