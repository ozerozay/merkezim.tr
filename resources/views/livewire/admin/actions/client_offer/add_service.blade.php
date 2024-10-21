<?php

use App\Models\Service;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public bool $show = false;

    public $branch_ids = null;

    #[Rule('required', as: 'Hizmet')]
    public $service_id = [];

    #[Rule('required', as: 'Hediye')]
    public $gift = false;

    #[Rule('required')]
    public int $quantity = 1;

    public $service_collection;

    public function mount()
    {
        $this->service_collection = $this->services();
    }

    public function services()
    {
        $branch_id = $this->branch_ids;

        return Service::query()
            ->where('active', true)
            ->whereHas('category.branches', function ($q) use ($branch_id) {
                $q->whereIn('id', $branch_id);
            })
            ->whereHas('category', function ($q) {
                $q->where('active', true);
            })
            ->with('category.branches')
            ->orderBy('name')
            ->get();
    }

    public function add_service()
    {
        $this->validate();
        $this->dispatch('service-added', $this->validate());
        $this->service_id = [];
    }

    /*public function with(): array
    {
        return [
            'services' => $this->services(),
        ];
    }*/

    #[On('reload-add-service')]
    public function reload_add_service($branch): void
    {
        $this->branch_ids = $branch;
        $this->service_collection = $this->services();
    }
};

?>


<div>
    <x-button label="Hizmet Ekle" icon="o-plus" @click="$wire.show = true" spinner class="btn-warning btn-sm" responsive />

    <x-modal wire:model="show" title="Hizmet Ekle">
        <x-form wire:submit="add_service">
            <x-choices-offline
                label="Hizmet"
                wire:model="service_id"
                :options="$service_collection"
                option-sub-label="category.name"
                option-avatar="cover"
                icon="o-magnifying-glass"
                hint="Hizmet Ara"
                searchable />

                <livewire:components.form.number_dropdown label="Seans" wire:model="quantity" />

                <x-checkbox label="Hediye" wire:model="gift" />

            <x-slot:actions>
                <x-button label="İptal" @click="$wire.show = false" />
                <x-button label="Ekle" type="submit" spinner="add_service" icon="o-paper-airplane" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>