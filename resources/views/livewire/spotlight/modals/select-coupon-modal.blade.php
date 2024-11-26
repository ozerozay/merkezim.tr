<x-custom-modal title="Kupon">
    <x-slot:body>
        <livewire:components.client.client_coupon_dropdown
            wire:key="csdpd-{{ Str::random() }}"
            label="Kupon"
            wire:model="coupon_id"
            :client_id="$client->id"/>
    </x-slot:body>

</x-custom-modal>
