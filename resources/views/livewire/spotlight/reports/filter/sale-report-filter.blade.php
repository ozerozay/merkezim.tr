<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih" wire:model="date_range"
            mode="range" />
        <livewire:components.form.branch_multi_dropdown wire:key="jdcccc-222" wire:model="branches" />
        <livewire:components.form.sale_type_multi_dropdown wire:key="ttttt-222" wire:model="select_type_id" />
        <livewire:components.form.sale_status_multi_dropdown wire:key="tttxxtt-222" wire:model="select_status_id" />
        <livewire:components.form.staff_multi_dropdown wire:key="stff-222" wire:model="select_staff_id" />
    </x-slide-over>
</div>
