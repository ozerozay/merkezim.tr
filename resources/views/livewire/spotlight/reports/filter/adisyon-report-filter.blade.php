<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih"
                                            wire:model="date_range"
                                            mode="range"/>
        <livewire:components.form.branch_multi_dropdown wire:key="1weq-222-{{ Str::random(10) }}"
                                                        wire:model="branches"/>
        <livewire:components.form.staff_multi_dropdown label="Oluşturan" wire:key="stff-222-{{ Str::random(10) }}"
                                                       wire:model="staffs"/>
        <livewire:components.form.client_dropdown wire:key="gfcxb-222-{{ Str::random(10) }}" wire:model="client"/>
    </x-slide-over>
</div>
