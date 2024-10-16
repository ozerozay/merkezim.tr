<?php

use App\Models\Service;
use App\Traits\ResetsPaginationWhenPropsChanges;
use App\Traits\LiveHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new
    #[Title('Hizmet')]
    class extends Component {
        use Toast, WithPagination, ResetsPaginationWhenPropsChanges;

        #[Url]
        public String $search = '';

        #[Url]
        public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

        public ?Service $service;

        #[On('service-saved')]
        #[On('service-cancel')]
        public function clear(): void
        {
            $this->reset();
        }

        public function services(): LengthAwarePaginator
        {
            return Service::query()
                ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%$this->search%"))
                ->orderBy(...array_values($this->sortBy))
                ->with('category')
                ->paginate(9);
        }

        public function edit(Service $service): void
        {
            $this->service = $service;
        }

        public function headers(): array
        {
            return [
                ['key' => 'id', 'label' => '#', 'class' => 'w-20'],
                ['key' => 'name', 'label' => 'Ad'],
                ['key' => 'category.name', 'label' => 'Kategori', 'sortable' => false],
                ['key' => 'gender', 'label' => 'Cinsiyet'],
                ['key' => 'price', 'label' => 'Fiyat'],
                ['key' => 'seans', 'label' => 'Seans'],
                ['key' => 'duration', 'label' => 'Süre'],
                ['key' => 'min_day', 'label' => 'Minimum'],
            ];
        }

        public function with(): array
        {
            return [
                'services' => $this->services(),
                'headers' => $this->headers(),
            ];
        }
    };
?>

<div>
    <x-header title="Hizmet" seperator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Ara..." wire:model.live.debounce="search" icon="o-magnifying-glass" clearable />
        </x-slot:middle>
        <x-slot:actions>
            <livewire:admin.settings.defination.service.create />

        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$headers" :rows="$services" @row-click="$wire.edit($event.detail.id)" :sort-by="$sortBy"
            with-pagination>
            @scope('cell_gender', $service)
            {{ LiveHelper::gender_text($service->gender)}}
            @endscope
            @scope('actions', $service)
            <x-badge value="{{ $service->active ? 'Aktif' : 'Pasif' }}"
                class="{{ $service->active ? 'badge-primary' : 'badge-error' }}" />
            @endscope
            @scope('cell_price', $service)
            {{ LiveHelper::price_text($service->price) }}
            @endscope
        </x-table>
    </x-card>
    <livewire:admin.settings.defination.service.edit wire:model="service" />

</div>