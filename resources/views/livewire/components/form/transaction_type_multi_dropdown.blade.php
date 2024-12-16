<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $type_id = [];

    public $types;

    public function mount(): void
    {
        foreach (\App\TransactionType::cases() as $type) {
            $this->types[] = [
                'id' => $type->name,
                'name' => $type->label(),
            ];
        }
    }
};
?>

<div>
    <x-choices-offline label="İşlem Çeşitleri" wire:model="type_id" :options="$types" />
</div>
