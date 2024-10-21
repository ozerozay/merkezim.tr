<?php

use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public bool $show = false;

    public ?int $package_id = null;

    public bool $gift = false;

    public int $quantity = 1;

    public $package_collection;

    #[On('card-service-add-package-update-collection')]
    public function updatePackageCollection($package_collection)
    {
        $this->package_collection = $package_collection;
    }

    public function addPackage()
    {
        $validator = Validator::make([
            'package_id' => $this->package_id,
            'gift' => $this->gift,
            'quantity' => $this->quantity,
        ], [
            'package_id' => 'required',
            'gift' => 'required|boolean',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->dispatch('card-package-added', $validator->validated());

        $this->reset('package_id', 'gift');
    }
};

?>
<div>
    <x-button label="Paket" icon="o-plus" @click="$wire.show = true" spinner class="btn-sm btn-outline" />
    <x-modal wire:model="show" title="Paket Ekle">
        <x-form wire:submit="addPackage">
            <x-choices-offline label="Paket" wire:model="package_id" :options="$package_collection" option-avatar="cover"
                icon="o-magnifying-glass" hint="Paket Ara" o-result-text="Paket bulunamadı." single searchable />
            <livewire:components.form.number_dropdown label="Adet" wire:model="quantity" />
            <x-checkbox label="Hediye" wire:model="gift" />
            <x-slot:actions>
                <x-button label="İptal" @click="$wire.show = false" />
                <x-button label="Ekle" type="submit" spinner="addPackage" icon="o-paper-airplane"
                    class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>