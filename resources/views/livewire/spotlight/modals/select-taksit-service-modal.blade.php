<x-custom-modal title="Taksite Hizmet Ekle">
    <x-slot:body>
        <x-choices
            wire:key="scscvsd-{{Str::random(20)}}"
            wire:model="service_ids"
            :options="$service_collection"
            option-sub-label="category.name"
            label="Hizmet"
            icon="o-magnifying-glass" searchable="true"/>
        <livewire:components.form.number_dropdown
            wire:key="numbeer-f-{{ Str::random(10) }}"
            label="Adet"
            wire:model="quantity"/>
    </x-slot:body>
    <x-slot:actions>
        <x-button label="Ekle" type="submit" spinner="save" icon="o-paper-airplane"
                  class="btn-primary"/>
    </x-slot:actions>
</x-custom-modal>
