
<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $status = [];

    public $statuses;

    public function mount(): void
    {
        foreach (\App\AgendaStatus::cases() as $status) {
            $this->statuses[] = [
                'id' => $status->name,
                'name' => $status->label(),
            ];
        }

    }
};
?>

<div wire:key="jsf-{{ Str::random() }}">
    <x-choices-offline wire:key="jxsf-{{ Str::random() }}" label="Durum" wire:model="status" single
                       :options="$statuses"/>
</div>
