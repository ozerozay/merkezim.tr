<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $talep_status = [];

    public $talep_statuses = [];

    public function mount()
    {
        foreach (\App\TalepStatus::cases() as $case) {
            $this->talep_statuses[] = [
                'id' => $case->name,
                'name' => $case->label(),
            ];
        }
    }
};
?>
<div wire:key="fjfjxxxxf4x{{ Str::random(10) }}">
    <x-choices-offline wire:key="fjgrv{{ Str::random(10) }}" label="Durum" wire:model="talep_status"
                       :options="$talep_statuses"
                       searchable/>
</div>
