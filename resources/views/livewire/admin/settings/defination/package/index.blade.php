<?php

use App\Models\Package;
use App\Traits\ResetsPaginationWhenPropsChanges;
use App\Traits\LiveHelper;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new
    #[Title('Hizmet')] class extends Component {
        use Toast, WithPagination, ResetsPaginationWhenPropsChanges;


        #[Url]
        public String $search = '';

        #[Url]
        public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

        public ?Package $package;

        #[On('package-saved')]
        #[On('package-cancel')]
        public function clear(): void
        {
            $this->reset();
        }

        public function packages(): LengthAwarePaginator
        {
            return Package::query()
                ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%$this->search%"))
                ->orderBy(...array_values($this->sortBy))
                ->withCount('items', 'branches')
                ->paginate(9);
        }

        public function edit(Package $package): void
        {
            $this->package = $package;
        }

        public function headers(): array
        {
            return [
                ['key' => 'id', 'label' => '#', 'class' => 'w-20'],
                ['key' => 'name', 'label' => 'Ad'],
                ['key' => 'gender', 'label' => 'Cinsiyet'],
                ['key' => 'items_count', 'label' => 'Hizmetler', 'sortBy' => 'items_count'],
                ['key' => 'branches_count', 'label' => 'Şube', 'sortBy' => 'branches_count'],
                ['key' => 'price', 'label' => 'Fiyat'],
                ['key' => 'buy_time', 'label' => 'Alınabilirlik'],
                ['key' => 'active', 'label' => 'Aktif', 'sortable' => false]
            ];
        }

        public function with(): array
        {
            return [
                'packages' => $this->packages(),
                'headers' => $this->headers(),
            ];
        }
    };
?>


<div>
    <x-header title="Paket" seperator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Ara..." wire:model.live.debounce="search" icon="o-magnifying-glass" clearable />
        </x-slot:middle>
        <x-slot:actions>
            <livewire:admin.settings.defination.package.create />

        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$headers" :rows="$packages" @row-click="$wire.edit($event.detail.id)" :sort-by="$sortBy"
            with-pagination>
            @scope('cell_gender', $service)
            {{ LiveHelper::gender_text($service->gender)}}
            @endscope
            @scope('cell_active', $package)
            <x-badge value="{{ $package->active ? 'Aktif' : 'Pasif' }}"
                class="{{ $package->active ? 'badge-primary' : 'badge-error' }}" />
            @endscope
            @scope('actions', $package)
            <x-button label="Hizmet Ekle" link="{{ route('admin.settings.defination.package.items', ['package' => $package->id]) }}" spinner />
            @endscope
            @scope('cell_price', $package)
            {{ LiveHelper::price_text($package->price) }}
            @endscope
            @scope('cell_buy_time', $package)
            {{ $package->buy_time == 0 ? 'SINIRSIZ' : $package->buy_time }}
            @endscope
        </x-table>
    </x-card>
    <livewire:admin.settings.defination.package.edit wire:model="package" />

</div>