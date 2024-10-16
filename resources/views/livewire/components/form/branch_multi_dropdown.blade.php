<?php

use App\Models\Branch;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {

    #[Modelable]
    public $branch_ids = [];

    public $branches;

    public function mount()
    {
        $this->branches = (new Branch)->branch_staff_active()
        ->get();
        if ($this->branches->count() > 0){
            $this->dispatch('branch-id-update', $this->branches->first()->id);
        }
    }
};
?>
<div>
    @if ($this->branches->count() > 1)
    <x-choices-offline label="Şubeler" wire:model="branch_ids" :options="$branches" /> @endif
</div>