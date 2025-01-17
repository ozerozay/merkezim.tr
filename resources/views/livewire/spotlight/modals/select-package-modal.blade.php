<x-custom-modal title="Paket Ekle">
    <x-slot:body>
        <x-choices
            wire:key="scssde-{{Str::random(20)}}"
            wire:model="package_ids"
            :options="$package_collection"
            option-sub-label="price"
            label="Paket"
            icon="o-magnifying-glass" searchable="true"/>
        <livewire:components.form.number_dropdown
            wire:key="number-f-{{ Str::random(10) }}"
            label="Adet"
            wire:model="quantity"/>
        <x-checkbox label="Hediye" wire:model="gift"/>
    </x-slot:body>
    <x-slot:actions>
        <x-button label="Ekle" type="submit" spinner="save" icon="o-paper-airplane"
                  class="btn-primary"/>
    </x-slot:actions>
</x-custom-modal>
