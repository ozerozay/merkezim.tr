<?php

use App\Models\SaleType;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $status = null;

    public $statuses;

    public $expireFreeze = false;

    public function mount(): void
    {
        $this->statuses = [
            [
                'id' => \App\SaleStatus::success->name,
                'name' => \App\SaleStatus::success->label(),
            ],
            [
                'id' => \App\SaleStatus::cancel->name,
                'name' => \App\SaleStatus::cancel->label(),
            ]
        ];

        if ($this->expireFreeze) {
            $this->statuses[] = [
                'id' => \App\SaleStatus::expired->name,
                'name' => App\SaleStatus::expired->label()
            ];
            $this->statuses[] = [
                'id' => \App\SaleStatus::freeze->name,
                'name' => App\SaleStatus::freeze->label()
            ];
        }
    }
};
?>

<div>
    <x-choices-offline
        label="Durum"
        single
        wire:model.live="status"
        :options="$statuses"/>
</div>
