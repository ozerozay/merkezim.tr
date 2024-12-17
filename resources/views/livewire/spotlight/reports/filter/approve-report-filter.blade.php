<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih" wire:model="date_range"
            mode="range" />
        <livewire:components.form.branch_multi_dropdown wire:key="jdwcccc-222" wire:model="branches" />
        <livewire:components.form.staff_multi_dropdown label="Oluşturan" wire:key="stff-222" wire:model="staffs_create" />
        <livewire:components.form.staff_multi_dropdown label="Onaylayan" wire:key="stfxf-222"
            wire:model="staffs_approve" />
        <livewire:components.form.approve_type_multi_dropdown wire:key="stfxcf-222" wire:model="types" />
        <livewire:components.form.approve_status_multi_dropdown wire:key="stfxccccf-222" wire:model="statuses" />
    </x-slide-over>
</div>
