

<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $type = [];

    public $types;

    public function mount(): void
    {
        foreach (\App\ApproveTypes::cases() as $status) {
            $this->types[] = [
                'id' => $status->name,
                'name' => $status->label(),
            ];
        }

    }
};
?>

<div>
    <x-choices-offline label="Onay Çeşitleri" wire:model="type" :options="$types" />
</div>
