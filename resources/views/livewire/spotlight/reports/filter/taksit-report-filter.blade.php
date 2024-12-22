<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih"
                                            wire:model="date_range"
                                            mode="range"/>
        <livewire:components.form.branch_multi_dropdown wire:key="jdccc-{{ Str::random(10) }}" wire:model="branches"/>
        <livewire:components.form.sale_status_multi_dropdown wire:key="tttxx-{{ Str::random(10) }}"
                                                             wire:model="select_status_id"/>
        <x-choices-offline label="Ödeme Durumu" wire:key="xxx{{ Str::random(10) }}" :options="$select_remaining"
                           wire:model="select_remaining_id" single/>
        <x-checkbox label="Sadece kilitli taksitleri göster" wire:model="select_lock_id"/>
    </x-slide-over>
</div>
