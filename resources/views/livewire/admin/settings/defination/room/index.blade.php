<?php

use App\Models\ServiceRoom;
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

    public ?ServiceRoom $room;

    #[On('room-saved')]
    #[On('room-cancel')]
    public function clear(): void
    {
        $this->reset();
    }

    public function rooms(): LengthAwarePaginator
    {
        return ServiceRoom::query()
            ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->with('categories','branch')
            ->paginate(9);
    }

    public function edit(ServiceRoom $room): void
    {
        $this->room = $room;
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-20'],
            ['key' => 'branch.name', 'label' => 'Şube', 'sortable' => false],
            ['key' => 'name', 'label' => 'Ad'],
            ['key' => 'category', 'label' => 'Kategoriler', 'sortable' => false],
        ];
    }

    public function with(): array
    {
        return [
            'rooms' => $this->rooms(),
            'headers' => $this->headers(),
        ];
    }
};

?>
<div>
    <x-header title="Oda" seperator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Ara..." wire:model.live.debounce="search" icon="o-magnifying-glass" clearable />
        </x-slot:middle>
        <x-slot:actions>
            <livewire:admin.settings.defination.room.create />

        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table
            :headers="$headers"
            :rows="$rooms"
            @row-click="$wire.edit($event.detail.id)"
            :sort-by="$sortBy"
            with-pagination>
            @scope('actions', $room)
            <x-badge value="{{ $room->active ? 'Aktif' : 'Pasif' }}" class="{{ $room->active ? 'badge-primary' : 'badge-error' }}" />
            @endscope
            @scope('cell_category', $room)
            <u>{{ $room->category_names() }}</u>
            @endscope
        </x-table>
    </x-card>

    <livewire:admin.settings.defination.room.edit wire:model="room" />
</div>