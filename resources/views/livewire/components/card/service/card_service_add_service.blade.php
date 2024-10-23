<?php

use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public bool $show = false;

    public $service_id = [];

    public $gift = false;

    public int $quantity = 1;

    public $service_collection;

    #[On('card-service-add-service-update-collection')]
    public function updateServiceCollection($service_collection)
    {
        $this->service_collection = $service_collection;
    }

    public function addService()
    {
        $validator = Validator::make([
            'service_id' => $this->service_id,
            'gift' => $this->gift,
            'quantity' => $this->quantity,
        ], [
            'service_id' => 'required|array',
            'gift' => 'required|boolean',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->dispatch('card-service-added', $validator->validated());

        $this->reset('service_id', 'gift');

    }
};
?>
<div>
    <x-button label="Hizmet" icon="o-plus" @click="$wire.show = true" spinner class="btn-sm btn-outline" />
    <x-modal wire:model="show" title="Hizmet">
        <x-form wire:submit="addService">
            <x-choices-offline label="Hizmet" wire:model="service_id" :options="$service_collection"
                option-sub-label="category.name" option-avatar="cover" icon="o-magnifying-glass" hint="Hizmet Ara"
                no-result-text="Hizmet bulunamadı."
                searchable
                 />
            <livewire:components.form.number_dropdown label="Seans" wire:model="quantity" />
            <x-checkbox label="Hediye" wire:model="gift" />
            <x-slot:actions>
                <x-button label="İptal" @click="$wire.show = false" />
                <x-button label="Ekle" type="submit" spinner="addService" icon="o-paper-airplane"
                    class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>