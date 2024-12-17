<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih" wire:model="date_range"
            mode="range" />
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Bitiş Tarihi"
            wire:model="date_range_expire" mode="range" />
        <livewire:components.form.branch_multi_dropdown wire:key="jdwcccc-222" wire:model="branches" />
        <livewire:components.form.staff_multi_dropdown label="Oluşturan" wire:key="stff-2222" wire:model="staffs" />
        <livewire:components.form.offer_status_multi_dropdown label="Oluşturan" wire:key="stff-x222"
            wire:model="staffs" />
    </x-slide-over>
</div>
