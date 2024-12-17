<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih" wire:model="date_range"
            mode="range" />
        <livewire:components.form.branch_multi_dropdown wire:key="jdwcccc-222" wire:model="branches" />
        <livewire:components.form.staff_multi_dropdown label="Oluşturan" wire:key="stff-222" wire:model="staff_create" />
        <livewire:components.form.client_multi_dropdown wire:key="jdwcccc-222" wire:model="client" />
        <livewire:components.form.product_multi_dropdown wire:key="jdwcccc-222" wire:model="products" />
    </x-slide-over>
</div>
