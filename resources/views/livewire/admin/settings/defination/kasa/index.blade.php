<?php

use App\Models\Kasa;
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
    public string $search = '';

    #[Url]
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public ?Kasa $kasa;

    #[On('kasa-saved')]
    #[On('kasa-cancel')]
    public function clear(): void
    {
        $this->reset();
    }

    public function kasas(): LengthAwarePaginator
    {
        return Kasa::query()
            ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(9);
    }

    public function edit(Kasa $kasa): void
    {
        $this->kasa = $kasa;
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-20'],
            ['key' => 'branch.name', 'label' => 'Şube', 'class' => 'w-40', 'sortable' => false],
            ['key' => 'name', 'label' => 'Ad'],
        ];
    }

    public function with(): array
    {
        return [
            'kasas' => $this->kasas(),
            'headers' => $this->headers(),
        ];
    }
};
?>

<div>
    <x-header title="Kasa" seperator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Ara..." wire:model.live.debounce="search" icon="o-magnifying-glass" clearable />
        </x-slot:middle>
        <x-slot:actions>
            <livewire:admin.settings.defination.kasa.create />

        </x-slot:actions>
    </x-header>

    <x-card>
        <x-card>
            <x-table
                :headers="$headers"
                :rows="$kasas"
                @row-click="$wire.edit($event.detail.id)"
                :sort-by="$sortBy"
                with-pagination>
                @scope('actions', $kasa)
                <x-badge value="{{ $kasa->active ? 'Aktif' : 'Pasif' }}" class="{{ $kasa->active ? 'badge-primary' : 'badge-error' }}" />
                @endscope
            </x-table>
        </x-card>
    </x-card>

    <livewire:admin.settings.defination.kasa.edit wire:model="kasa" />
</div>