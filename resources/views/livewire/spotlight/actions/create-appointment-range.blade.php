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
            wire:model="date_find_range"
            wire:key="date-field-{{ Str::random(10) }}"
            minDate="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
            mode="range"/>

        <x-button label="Randevu Tara" class="btn-primary" wire:click="findAvailableAppointmentsRange"/>
        @if (count($available_appointments_range) > 0)
            <x-hr/>
            <x-select-group label="Uygun randevu tarihleri" :options="$available_appointments_range"
                            wire:model="selected_available_appointments_range"/>
            <x-input wire:model="message" label="Randevu notunuz"/>
        @endif
    </x-slide-over>
</div>
