<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih"
                                            wire:model="date_range"
                                            mode="range"/>
        <livewire:components.form.branch_multi_dropdown wire:key="jdcccxczc-{{ Str::random(10) }}"
                                                        wire:model="branches"/>
        <livewire:components.form.sale_status_multi_dropdown wire:key="tttxxafastt-{{ Str::random(10) }}"
                                                             wire:model="select_status_id"/>
        <livewire:components.form.staff_multi_dropdown wire:key="stfxcf-{{ Str::random(10) }}"
                                                       wire:model="select_staff_id"/>
        <livewire:components.form.service_multi_dropdown wire:key="sxxtfzxcvf-{{ Str::random(10) }}"
                                                         wire:model="select_service_id"/>
        <x-checkbox label="Bitenleri gösterme" wire:model="select_remaining_id"/>
        <x-checkbox label="Sadece hediyeleri göster" wire:model="select_gift_id"/>
    </x-slide-over>
</div>
