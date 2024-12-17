<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih" wire:model="date_range"
            mode="range" />
        <livewire:components.form.branch_multi_dropdown wire:key="jdwcccc-222" wire:model="branches" />
        <livewire:components.form.staff_multi_dropdown label="Kullanıcı" wire:key="stff-222" wire:model="staffs" />
        <livewire:components.form.talep_type_multi_dropdown label="Talep Tipi" wire:key="stff-222" wire:model="types" />
        <livewire:components.form.talep_stasus_multi_dropdown label="Durum" wire:key="stff-222"
            wire:model="statuses" />
    </x-slide-over>
</div>
