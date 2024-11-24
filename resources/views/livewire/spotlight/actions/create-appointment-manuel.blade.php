<div>
    <x-slide-over title="{{ $category->name }} - Randevu" subtitle="{{ $client->name ?? '' }}">
        @if($this->calculateDuration() > 0)
            <x-button>
                Süre:
                <x-badge value="{{ $this->calculateDuration() }} dk" class="badge-neutral"/>
            </x-button>
        @endif
        <livewire:components.client.client_service_multi_dropdown
            wire:model.live.debounce.1000ms="service_ids"
            wire:key="ccsmd-{{ Str::random(10) }}"
            :client_id="$client->id"
            :category_id="$category->id"/>
        <livewire:components.form.date_time
            label="Tarih"
            wire:model="date"
            wire:key="date-field-{{ Str::random(10) }}"
            minDate="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
            :enableTime="true"/>
        <livewire:components.client.client_service_room_dropdown
            wire:model="room_id"
            wire:key="ccsmd-{{ Str::random(10) }}"
            :client_id="$client->id"
            :category_id="$category->id"/>
        <x-input wire:model="message" label="Randevu notunuz"/>
    </x-slide-over>
</div>
