<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih" wire:model="date_range"
            mode="range" />
        <livewire:components.form.appointment_status_dropdown wire:key="tttxxtt-222" wire:model="select_status_id" />
        <livewire:components.form.branch_multi_dropdown wire:key="jdcccc-222" wire:model="branches" />
        <livewire:components.form.staff_multi_dropdown label="Oluşturan" wire:key="stff-222"
            wire:model="select_create_staff_id" />
        <livewire:components.form.staff_multi_dropdown label="İşlemi Yapan" wire:key="stff-222"
            wire:model="select_finish_staff_id" />
    </x-slide-over>
</div>
