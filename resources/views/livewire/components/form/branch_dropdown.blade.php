<?php

use App\Models\Branch;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public ?int $branch_id;

    public $branches;

    public function mount(): void
    {
        $this->branches = (new Branch)->branch_staff_active()
            ->get();
        if ($this->branches->count() > 0) {
            $this->dispatch('branch-id-update', $this->branches->first()->id);
        }
    }
};
?>
<div wire:key="branchdiv-{{ Str::random(10) }}">
    @if ($branches->count() > 1)
        <x-choices-offline
            wire:key="branch-{{ Str::random(10) }}"
            label="Şube"
            single
            wire:model="branch_id"
            :options="$branches"/>
    @endif
</div>



