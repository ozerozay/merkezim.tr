<?php

use App\Models\SaleType;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $sale_type_id = null;

    public $sale_types;

    public function mount()
    {
        $this->sale_types = SaleType::where('active', true)->get();
    }
};
?>

<div>
    <x-choices-offline
    label="Satış Tipi"
    single
    wire:model="sale_type_id"
    :options="$sale_types" />
</div>