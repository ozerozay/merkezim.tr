<?php

use App\Models\Branch;
use App\Models\ServiceCategory;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {

    #[Modelable]
    public $category_ids = [];

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
    label="Kategoriler"
    wire:model="category_ids"
    :options="$categories" />
</div>