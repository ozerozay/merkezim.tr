<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih"
                                            wire:model="date_range"
                                            mode="range"/>
        <livewire:components.form.branch_multi_dropdown wire:key="jdwcccc-{{ Str::random(10) }}" wire:model="branches"/>
        <livewire:components.form.staff_multi_dropdown label="Oluşturan" wire:key="stbnff-{{ Str::random(10) }}"
                                                       wire:model="staffs_create"/>
        <livewire:components.form.staff_multi_dropdown label="Onaylayan" wire:key="stfxqqxf-{{ Str::random(10) }}"
                                                       wire:model="staffs_approve"/>
        <livewire:components.form.approve_type_multi_dropdown wire:key="stfaxcf33f-{{ Str::random(10) }}"
                                                              wire:model="types"/>
        <livewire:components.form.approve_status_multi_dropdown wire:key="stfxcccczxaxvf-{{ Str::random(10) }}"
                                                                wire:model="statuses"/>
    </x-slide-over>
</div>
