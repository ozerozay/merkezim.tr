<div>
    <x-slide-over title="Hizmet Kullandır" subtitle="{{ $client->name ?? '' }}">
        <livewire:components.client.client_service_multi_dropdown
            wire:key="client-smd-{{ Str::random(10) }}"
            wire:model="service_ids"
            :client_id="$client->id"/>
        <livewire:components.form.number_dropdown
            wire:key="number-dr-{{ Str::random(10) }}"
            wire:model="seans" label="Adet" :includeZero="false"/>
        <x-input label="Açıklama" wire:model="message"/>
    </x-slide-over>
</div>
