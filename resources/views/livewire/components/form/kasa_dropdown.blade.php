<?php

use App\Models\Kasa;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $kasa_id;

    public $kasas;

    public $branch = null;

    public $label = 'Kasa';

    public function mount()
    {
        if ($this->branch == null) {
            $this->kasas = Kasa::query()
                ->where('active', true)
                ->whereHas('branch', function ($q) {
                    $q->whereIn('id', Auth::user()->staff_branches);
                })
                ->with('branch')
                ->get();
        } else {
            $this->kasas = Kasa::query()
                ->where('active', true)
                ->whereIn('branch_id', $this->branch)
                ->with('branch')
                ->get();
        }
    }
};
?>
<div wire:key="djxn-{{Str::random(10)}}">
    <x-choices-offline wire:key="djn-{{Str::random(10)}}"
                       :label="$label"
                       option-sub-label="branch.name" single
                       class="absolute"
                       wire:model="kasa_id" :options="$kasas"/>
</div>
