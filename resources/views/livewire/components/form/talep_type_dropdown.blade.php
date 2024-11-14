<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $talep_type = null;

    public $talep_types;

    public function mount()
    {
        foreach (\App\TalepType::cases() as $case) {
            $this->talep_types[] = [
                'id' => $case->name,
                'name' => $case->label()
            ];
        }
    }
};
?>

<div>
    <x-choices-offline label="Talep Tipi" wire:model="talep_type" :options="$talep_types" single/>
</div>
