<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih"
                                            wire:model="date_range"
                                            mode="range"/>
        <livewire:components.form.branch_multi_dropdown wire:key="jdcvvccc-{{ Str::random(10) }}"
                                                        wire:model="branches"/>
        <livewire:components.form.sale_type_multi_dropdown wire:key="ttttt-{{ Str::random(10) }}"
                                                           wire:model="select_type_id"/>
        <livewire:components.form.sale_status_multi_dropdown wire:key="tttxxxxatt-{{ Str::random(10) }}"
                                                             wire:model="select_status_id"/>
        <livewire:components.form.staff_multi_dropdown wire:key="stff-{{ Str::random(10) }}"
                                                       wire:model="select_staff_id"/>
    </x-slide-over>
</div>
