<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih"
                                            wire:model="date_range"
                                            mode="range"/>
        <livewire:components.form.date_time wire:key="date-fccf-{{ Str::random(10) }}" label="Bitiş Tarihi"
                                            wire:model="date_range_expire" mode="range"/>
        <livewire:components.form.branch_multi_dropdown wire:key="jdwwewrcccc-{{ Str::random(10) }}"
                                                        wire:model="branches"/>
        <livewire:components.form.staff_multi_dropdown label="Oluşturan" wire:key="stffaa-{{ Str::random(10) }}"
                                                       wire:model="staffs"/>
        <livewire:components.form.offer_status_multi_dropdown label="Durum" wire:key="stfxxfaa-{{ Str::random(10) }}"
                                                              wire:model="statuses"/>
    </x-slide-over>
</div>
