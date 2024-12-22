<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="datewddd-ff-{{ Str::random(10) }}" label="Tarih"
                                            wire:model="date_range"
                                            mode="range"/>
        <livewire:components.form.date_time wire:key="datefxf-{{ Str::random(10) }}" label="Tarih"
                                            wire:model="date_range_end" mode="range"/>
        <livewire:components.form.staff_multi_dropdown label="Oluşturan" wire:key="stffcc-{{ Str::random(10) }}"
                                                       wire:model="staffs"/>
        <livewire:components.form.branch_multi_dropdown label="Şube" wire:key="stffcxac-{{ Str::random(10) }}"
                                                        wire:model="branches"/>
    </x-slide-over>
</div>
