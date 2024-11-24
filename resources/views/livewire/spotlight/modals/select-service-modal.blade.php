<x-custom-modal title="Hizmet Ekle">
    <x-slot:body>
        <x-choices
            wire:key="scssd-{{Str::random(20)}}"
            wire:model="service_ids"
            :options="$service_collection"
            option-sub-label="category.name"
            label="Hizmet"
            icon="o-magnifying-glass" searchable="true"/>
        <livewire:components.form.number_dropdown
            wire:key="number-f-{{ Str::random(10) }}"
            label="Seans"
            wire:model="quantity"/>
        <x-checkbox label="Hediye" wire:model="gift"/>
    </x-slot:body>
    <x-slot:actions>
        <x-button label="Ekle" type="submit" spinner="save" icon="o-paper-airplane"
                  class="btn-primary"/>
    </x-slot:actions>
</x-custom-modal>
