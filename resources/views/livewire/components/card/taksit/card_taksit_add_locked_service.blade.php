<?php

use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public bool $show = false;

    public $service_ids = [];

    public int $quantity = 1;

    public $service_collection = [];

    public $taksit_id = null;

    #[On('card-taksit-add-locked-update-collection')]
    public function updateServiceCollection($service_collection)
    {
        $this->service_collection = $service_collection;
    }

    #[On('card-taksit-add-locked-clicked')]
    public function openModalForm($taksit_id)
    {
        $this->taksit_id = $taksit_id;
        $this->service_ids = [];
        $this->quantity = 1;
        $this->show = true;
    }

    public function save()
    {

        $validator = Validator::make([
            'service_ids' => $this->service_ids,
            'quantity' => $this->quantity,
            'taksit_id' => $this->taksit_id,
        ], [
            'service_ids' => 'required|array',
            'quantity' => 'required|integer',
            'taksit_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->dispatch('taksit-locked-service-added', $validator->validated());

        $this->show = false;

    }
};
?>

<div>
    <x-modal wire:model="show" title="Ürün">
        <x-form wire:submit="save">
        <x-choices-offline label="Hizmet" wire:model="service_ids" :options="$service_collection"
                option-sub-label="category.name" option-avatar="cover" icon="o-magnifying-glass" hint="Hizmet Ara"
                no-result-text="Hizmet bulunamadı."
                searchable
                 />
            <livewire:components.form.number_dropdown label="Adet" wire:model="quantity" />
            <x-slot:actions>
                <x-button label="İptal" @click="$wire.show = false" />
                <x-button label="Ekle" type="submit" spinner="save" icon="o-paper-airplane"
                    class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>