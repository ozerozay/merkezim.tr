<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih" wire:model="date_range"
            mode="range" />
        <livewire:components.form.date_time wire:key="datefxf-{{ Str::random(10) }}" label="Tarih"
            wire:model="date_range_end" mode="range" />
        <livewire:components.form.staff_multi_dropdown label="Oluşturan" wire:key="stff-222" wire:model="staffs" />
    </x-slide-over>
</div>
