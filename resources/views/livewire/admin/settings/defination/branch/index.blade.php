<?php

use App\Models\Branch;
use App\Traits\ResetsPaginationWhenPropsChanges;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, WithPagination, ResetsPaginationWhenPropsChanges;

    #[Url]
    public String $search = '';

    #[Url]
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public ?Branch $branch;

    #[On('branch-saved')]
    #[On('branch-cancel')]
    public function clear(): void
    {
        $this->reset();
    }

    public function edit(Branch $branch): void
    {
        $this->branch = $branch;
    }

    public function branches(): LengthAwarePaginator
    {
        return Branch::query()
            ->whereIn('id', auth()->user()->staff_branches)
            ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(9);
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-20'],
            ['key' => 'name', 'label' => 'Ad'],
        ];
    }

    public function with(): array
    {
        return [
            'branches' => $this->branches(),
            'headers' => $this->headers(),
        ];
    }
};
?>

<div>
    <x-header title="Şube" seperator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Ara..." wire:model.live.debounce="search" icon="o-magnifying-glass" clearable />
        </x-slot:middle>
        <x-slot:actions>
            <livewire:admin.settings.defination.branch.create />
        </x-slot:actions>
    </x-header>

    <x-card>
        <x-card>
            <x-table
                :headers="$headers"
                :rows="$branches"
                @row-click="$wire.edit($event.detail.id)"
                :sort-by="$sortBy"
                with-pagination>
                @scope('actions', $branch)
                <x-badge value="{{ $branch->active ? 'Aktif' : 'Pasif' }}" class="{{ $branch->active ? 'badge-primary' : 'badge-error' }}" />
                @endscope
            </x-table>
        </x-card>
    </x-card>

    <livewire:admin.settings.defination.branch.edit wire:model="branch" />
</div>