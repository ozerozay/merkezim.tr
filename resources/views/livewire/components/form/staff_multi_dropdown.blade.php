<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $staff_ids = [];

    public $staffs;

    public $label = 'Personeller';

    public function mount()
    {
        $this->staffs = User::query()
            ->where('active', true)
            ->role(['admin', 'staff'])
            ->whereHas('staff_branch', function ($q) {
                $q->whereIn('id', Auth::user()->staff_branches);
            })
            ->get();
    }
};
?>

<div wire:key="cvbzjnxczxk-{{ Str::random(10) }}">
    <x-choices-offline wire:key="cvbzjk-{{ Str::random(10) }}" :label="$label" wire:model="staff_ids"
                       :options="$staffs"/>
</div>
