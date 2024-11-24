<div>
    <x-slide-over title="Etiket Belirle" subtitle="{{ $client->name ?? '' }}">
        <livewire:components.client.client_label_multi_dropdown
            wire:model="client_labels"
            :client_id="$client->id"/>
    </x-slide-over>
</div>
