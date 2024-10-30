<?php

use App\Models\User;
use App\Traits\ResetsPaginationWhenPropsChanges;
use App\Traits\StringHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use ResetsPaginationWhenPropsChanges, Toast, WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    #[On('client-saved')]
    public function clear(): void
    {
        $this->reset();
    }

    public function clients(): LengthAwarePaginator
    {
        $search = $this->search;

        return User::query()
            ->select(['id', 'name', 'unique_id', 'branch_id', 'created_at'])
            ->when($this->search, fn(Builder $q) => $q->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . StringHelper::strUpper($search) . '%')
                    ->orWhere('unique_id', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            }))
            ->whereHas('client_branch', function ($q) {
                $q->whereIn('id', auth()->user()->staff_branches);
            })
            ->with('client_branch:id,name')
            ->latest()
            ->orderBy(...array_values($this->sortBy))
            ->paginate(9);
    }

    public function headers(): array
    {
        return [
            ['key' => 'client_branch.name', 'label' => 'Şube', 'class' => 'w-20', 'sortable' => false],
            ['key' => 'unique_id', 'label' => '#', 'class' => 'w-20', 'sortable' => false],
            ['key' => 'created_at', 'class' => 'w-40 hidden lg:table-cell', 'label' => 'Tarih'],
            ['key' => 'name', 'label' => 'Ad'],
        ];
    }

    public function with(): array
    {
        return [
            'clients' => $this->clients(),
            'headers' => $this->headers(),
        ];
    }
};
?>

<div>
    <x-header title="Danışan" seperator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Ara..." wire:model.live.debounce="search" icon="o-magnifying-glass" clearable/>
        </x-slot:middle>
        <x-slot:actions>
        </x-slot:actions>
    </x-header>
    <x-card>
        <x-table :headers="$headers" :rows="$clients" striped link="{{ route('admin.clients') }}/{id}"
                 :sort-by="$sortBy"
                 with-pagination>
        </x-table>
    </x-card>
</div>
