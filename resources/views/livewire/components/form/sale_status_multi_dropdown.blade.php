<?php

use App\Models\SaleType;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $status = [];

    public $statuses;

    public function mount(): void
    {
        $this->statuses = [
            [
                'id' => \App\SaleStatus::waiting->name,
                'name' => \App\SaleStatus::waiting->label(),
            ],
            [
                'id' => \App\SaleStatus::success->name,
                'name' => \App\SaleStatus::success->label(),
            ],
            [
                'id' => \App\SaleStatus::cancel->name,
                'name' => \App\SaleStatus::cancel->label(),
            ],
            [
                'id' => \App\SaleStatus::expired->name,
                'name' => App\SaleStatus::expired->label(),
            ],
            [
                'id' => \App\SaleStatus::freeze->name,
                'name' => App\SaleStatus::freeze->label(),
            ],
        ];
    }
};
?>

<div>
    <x-choices-offline label="Durum" wire:model="status" :options="$statuses" />
</div>
