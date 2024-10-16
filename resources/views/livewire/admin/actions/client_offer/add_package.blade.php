<?php

use App\Models\Package;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public bool $show = false;

    public $branch = null;

    #[Rule('required', as: 'Paket')]
    public $package_id = null;

    #[Rule('required', as: 'Miktar')]
    public int $quantity = 1;

    #[Rule('required', as: 'Hediye')]
    public $gift = false;

    public function packages()
    {
        $branch_id = $this->branch;

        return Package::query()
            ->where('active', true)
            ->whereHas('branches', function ($q) use ($branch_id) {
                $q->where('id', $branch_id);
            })
            ->orderBy('name')
            ->get();
    }

    public function add_package()
    {
        $this->dispatch('package-added', $this->validate());
        $this->show = false;
    }

    public function with(): array
    {
        return [
            'packages' => $this->packages(),
        ];
    }
};
?>
<div>
<x-button label="Paket Ekle" icon="o-plus" @click="$wire.show = true" spinner class="btn-primary btn-sm" responsive />

<x-modal wire:model="show" title="Paket Ekle">
    <x-form wire:submit="add_package">
        <x-choices-offline
            label="Paket"
            wire:model="package_id"
            :options="$packages"
            option-avatar="cover"
            icon="o-magnifying-glass"
            hint="Paket Ara"
            single
            searchable />

            <livewire:components.form.number_dropdown label="Adet" wire:model="quantity" />

            <x-checkbox label="Hediye" wire:model="gift" />
        <x-slot:actions>
            <x-button label="İptal" @click="$wire.show = false" />
            <x-button label="Ekle" type="submit" spinner="add_package" icon="o-paper-airplane" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</x-modal>
</div>
