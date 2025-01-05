<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $staff_id = null;

    public $staffs;

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

<div wire:key="zzstaff-{{ Str::random(10) }}">
    <x-select wire:key="staxcgff-{{ Str::random(10) }}" label="Personel" wire:model="staff_id" :options="$staffs" single />
</div>