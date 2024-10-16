<?php

use App\Models\ServiceCategory;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $category_id = [];

    public $categories;

    public function mount()
    {
        $this->categories = (new ServiceCategory)->category_staff_active()
            ->get();
    }
};
?>
<div>
<x-choices-offline
    label="Kategori"
    single
    wire:model="category_id"
    :options="$categories" />
</div>