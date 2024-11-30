<div>
    <x-slide-over title="Paket Oluştur">
        <livewire:components.form.branch_dropdown wire:key="de33f-kas-{{ Str::random(10) }}" wire:model="branch_id" />
        <x-input label="Adı" wire:model="name" />
        <x-input label="Fiyatı" wire:model="price" suffix="₺" money />
        <livewire:components.form.gender_dropdown wire:key="xoe-{{ Str::random(10) }}" wire:model="gender" />
        <livewire:components.form.number_dropdown wire:key="xo3e-{{ Str::random(10) }}" wire:model="buy_time"
            label="Ne kadar alınabilir ? (0 Sınırsız)" max="100" includeZero="true" />
    </x-slide-over>
</div>
