<x-custom-modal title="Peşinat Ekle">
    <x-slot:body>
        <livewire:components.form.date_time
            label="Tarih"
            wire:model="date"
            wire:key="date-fieled-{{ Str::random(10) }}"/>
        <x-choices-offline
            wire:key="scsdddsd-{{Str::random(20)}}"
            wire:model="kasa_id"
            :options="$kasa_collection"
            option-sub-label="branch.name"
            label="Kasa"
            single
            icon="o-magnifying-glass" searchable="true"/>
        <x-input label="Tutar" wire:model="price" suffix="₺" money/>
    </x-slot:body>
    <x-slot:actions>
        <x-button label="Ekle" type="submit" spinner="save" icon="o-paper-airplane"
                  class="btn-primary"/>
    </x-slot:actions>
</x-custom-modal>
