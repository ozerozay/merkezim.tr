<div>
    <x-slide-over title="Hizmet Yükle" subtitle="{{ $client->name ?? '' }}">
        <livewire:components.client.client_sale_dropdown
            wire:key="client-sale-{{ Str::random(10) }}"
            wire:model="sale_id"
            :client_id="$client->id"/>
        <livewire:components.form.service_multi_dropdown
            wire:key="client-service-multi-{{ Str::random(10) }}"
            wire:model="service_ids"
            :branch_id="$branch"
            :gender="$gender"/>
        <livewire:components.form.number_dropdown
            wire:key="number-{{ Str::random(10) }}"
            wire:model="seans"
            label="Adet"
            :includeZero="false"/>
        <x-input label="Açıklama" wire:key="message-{{ Str::random(10) }}" wire:model="message"/>
        <x-checkbox label="Hediye" wire:key="gift-{{ Str::random(10) }}" wire:model="gift"/>
    </x-slide-over>
</div>
