<?php

use App\Models\User;
use App\Traits\StringHelper;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $client_id;

    public Collection $users;

    public $label = 'Danışan';

    public $dis = 'client-selected';

    public function mount()
    {
        $this->search();
    }

    public function search(string $value = ''): void
    {
        $selectedOption = User::where('unique_id', $this->client_id)->get();

        $this->users = User::query()
            ->where(function ($subQuery) use ($value) {
                $subQuery->where('name', 'like', '%'.StringHelper::strUpper($value).'%')
                    ->orWhere('unique_id', 'like', '%'.$value.'%')
                    ->orWhere('phone', 'like', '%'.$value.'%');
            })
            ->whereIn('branch_id', auth()->user()->staff_branches)
            ->take(5)
            ->latest()
            ->with('client_branch:id,name')
            ->get()
            ->merge($selectedOption);
    }
};
?>
<div>
<x-choices
    wire:model="client_id"
    :options="$users"
    option-sub-label="client_branch.name"
    :label="$label"
    icon="o-magnifying-glass"
    @change-selection="$dispatch('{{ $dis }}', {client: $event.detail.value})"
    single
    searchable />
</div>