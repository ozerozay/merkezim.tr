<?php

use App\Models\Masraf;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $masraf_id = [];

    public $masrafs;

    public function mount()
    {
        $this->masrafs = Masraf::where('active', true)
            ->whereHas('branch', function ($q) {
                $q->whereIn('id', auth()->user()->staff_branches);
            })
            ->with('branch:id,name')
            ->get();
    }
};
?>

<div>
    <x-choices-offline label="Masraf Tipi" option-sub-label="branch.name" wire:model="masraf_id" :options="$masrafs" />
</div>
