<div>
    <x-slide-over title="Tahsilat" subtitle="{{ $client->name ?? '' }}">
        <livewire:components.form.date_time label="Tarih" wire:model="date" wire:key="date-field-{{ Str::random(10) }}" />
        <livewire:components.client.client_taksit_dropdown wire:key="sdf-{{ Str::random(10) }}" :client_id="$client->id" />
        <livewire:components.form.kasa_dropdown wire:model="kasa_id" :branch="[$client->branch_id]" />
        <x-input label="Tutar" wire:model="price" suffix="₺" money />
    </x-slide-over>
</div>
