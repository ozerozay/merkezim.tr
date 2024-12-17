

<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $status = [];

    public $statuses;

    public function mount(): void
    {
        foreach (\App\ApproveStatus::cases() as $status) {
            $this->statuses[] = [
                'id' => $status->name,
                'name' => $status->label(),
            ];
        }

    }
};
?>

<div>
    <x-choices-offline label="Durum" wire:model="status" :options="$statuses" />
</div>
