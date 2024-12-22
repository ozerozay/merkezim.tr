<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih"
                                            wire:model="date_range"
                                            mode="range"/>
        <livewire:components.form.branch_multi_dropdown wire:key="jdwcccc-{{ Str::random() }}" wire:model="branches"/>
        <livewire:components.form.staff_multi_dropdown label="Kullanıcı" wire:key="satwcfvf-{{ Str::random() }}"
                                                       wire:model="staffs"/>
        <livewire:components.form.talep_type_multi_dropdown label="Talep Tipi" wire:key="stqafzcf-{{Str::random()}}"
                                                            wire:model="types"/>
        <livewire:components.form.talep_status_multi_dropdown label="Durum" wire:key="stfxacacacvf-{{Str::random()}}"
                                                              wire:model="statuses"/>
    </x-slide-over>
</div>
