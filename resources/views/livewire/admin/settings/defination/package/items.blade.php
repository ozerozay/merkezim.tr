<?php

use App\Models\Package;
use App\Models\PackageItem;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {

    use Toast;

    public Package $package;

    #[Rule(['items.*.quantity' => 'required|integer'])]
    public array $items = [];

    public function mount(): void
    {
        $this->package->load(['items.service.category']);
        $this->items = $this->package->items->toArray();
    }

    #[On('service-added')]
    public function refreshItems(): void
    {
        $this->items = $this->package->items->toArray();
    }

    public function updateQuantity(PackageItem $item, int $quantity)
    {
        $item->update([
            'quantity' => $quantity,
        ]);

        $this->package->refresh();
    }

    public function deleteItem(PackageItem $item): void
    {
        $item->delete();
        $this->success('Hizmet silindi.');

        $this->package->refresh();
    }

    public function headers(): array
    {
        return [
            ['key' => 'service.category.name', 'label' => 'Kategori', 'class' => 'hidden lg:table-cell'],
            ['key' => 'service.name', 'label' => 'Hizmet', 'sortable' => false],
            ['key' => 'quantity', 'label' => 'Adet'],
        ];
    }

    public function quantities(): Collection
    {
        $items = collect();

        collect(range(1, 100))->each(fn($item) => $items->add(['id' => $item, 'name' => $item]));

        return $items;
    }

    public function goBack()
    {
        return redirect()->back();
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'quantities' => $this->quantities()
        ];
    }
};

?>

<div>
    <x-card title="{{ $package->name }}" separator progress-indicator="updateQuantity" shadow class="mt-8">
        <x-slot:menu>
            <x-button label="Geri Dön" icon="o-arrow-left" spinner @click="window.history.back()" responsive />
            <livewire:admin.settings.defination.package.add_item :package="$package" />
        </x-slot:menu>

        <x-table :rows="$package->items" :headers="$headers">

            @scope('cell_quantity', $item, $quantities)
            <x-select wire:model.number="items.{{ $loop->index }}.quantity" :options="$quantities" wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                class="select-sm !w-14" />
            @endscope


            {{-- Actions scope --}}
            @scope('actions', $item)
            <x-button icon="o-trash" wire:click="deleteItem({{ $item->id }})" spinner class="btn-ghost text-error btn-sm" />
            @endscope
        </x-table>

        @if(!$package->items->count())
        <x-icon name="o-list-bullet" label="Hizmet yok." class="text-gray-400 mt-5" />
        @endif
    </x-card>

</div>