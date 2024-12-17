<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $status = [];

    public $statuses = [];

    public function mount()
    {
        foreach (\App\OfferStatus::cases() as $case) {
            $this->statuses[] = [
                'id' => $case->name,
                'name' => $case->label(),
            ];
        }
    }
};
?>
<div wire:key="fjfxjf4x{{ Str::random(10) }}">
    <x-choices-offline wire:key="fjfjf{{ Str::random(10) }}" label="Durum" wire:model="status" :options="$statuses"
        searchable />
</div>
