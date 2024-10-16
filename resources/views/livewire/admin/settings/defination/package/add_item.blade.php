<?php

use App\Exceptions\AppException;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public ?Package $package = null;

    public bool $show = false;

    #[Rule('required')]
    public ?int $service_id = null;

    #[Rule('required')]
    public int $quantity = 1;

    public Collection $services;

    public function mount(): void
    {
        $this->search();
    }

    public function save(): void
    {
        $data = $this->validate();

        DB::beginTransaction();

        $alreadyOnOrder = $this->package->items()->where('service_id', $data['service_id'])
            ->count();

        if ($alreadyOnOrder) {
            throw new AppException('Hizmet bulunuyor.');
        }

        PackageItem::create([
            'package_id' => $this->package->id,
            'service_id' => $data['service_id'],
            'quantity' => $data['quantity'],
        ]);

        $this->success('Hizmet Eklendi.');
        $this->reset(['service_id', 'quantity']);
        $this->dispatch('service-added');

        DB::commit();
    }

    public function search(string $value = ''): void
    {
        $selectedOption = Service::where('id', $this->service_id)->get();

        $this->services = Service::query()
            ->with('category')
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->orderBy('name')
            ->get()
            ->merge($selectedOption);
    }

    public function quantities(): Collection
    {
        $items = collect();

        collect(range(1, 100))->each(fn($item) => $items->add(['id' => $item, 'name' => $item]));

        return $items;
    }

    public function with(): array
    {
        return [
            'quantities' => $this->quantities()
        ];
    }
};
?>


<div>
    <x-button label="Ekle" icon="o-plus" @click="$wire.show = true" spinner class="btn-primary" responsive />

    <x-drawer wire:model="show" title="Hizmet Ekle" with-close-button separator right class="w-11/12 lg:w-1/3">
        <x-form wire:submit="save">
            <x-choices
                label="Hizmet"
                wire:model="service_id"
                :options="$services"
                option-sub-label="category.name"
                option-avatar="cover"
                icon="o-magnifying-glass"
                hint="Hizmet arayın"
                single
                searchable />

            <x-select label="Adet" wire:model="quantity" :options="$quantities" />

            <x-slot:actions>
                <x-button label="Ekle" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-drawer>
</div>