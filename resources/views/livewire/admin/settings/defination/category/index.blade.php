<?php

use App\Models\ServiceCategory;
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

    public ?ServiceCategory $category;

    #[On('category-saved')]
    #[On('category-cancel')]
    public function clear(): void
    {
        $this->reset();
    }

    public function categories(): LengthAwarePaginator
    {
        return ServiceCategory::query()
            ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->with('branches')
            ->paginate(9);
    }

    public function edit(ServiceCategory $category): void
    {
        $this->category = $category;
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-20'],
            ['key' => 'name', 'label' => 'Ad'],
            ['key' => 'branch', 'label' => 'Şube'],
            ['key' => 'price', 'label' => 'Birim Fiyatı'],
            ['key' => 'earn', 'label' => 'Ödül'],
        ];
    }

    public function with(): array
    {
        return [
            'categories' => $this->categories(),
            'headers' => $this->headers(),
        ];
    }
};
?>

<div>
    <x-header title="Hizmet Kategorisi" seperator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Ara..." wire:model.live.debounce="search" icon="o-magnifying-glass" clearable />
        </x-slot:middle>
        <x-slot:actions>
            <livewire:admin.settings.defination.category.create />

        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$headers" :rows="$categories" @row-click="$wire.edit($event.detail.id)" :sort-by="$sortBy"
            with-pagination>
            @scope('actions', $category)
            <x-badge value="{{ $category->active ? 'Aktif' : 'Pasif' }}"
                class="{{ $category->active ? 'badge-primary' : 'badge-error' }}" />
            @endscope
            @scope('cell_branch', $category)
            <u>{{ $category->branch_names() }}</u>
            @endscope
        </x-table>
    </x-card>

    <livewire:admin.settings.defination.category.edit wire:model="category" />
</div>