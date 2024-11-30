<div>
    <x-slide-over title="Ürün Oluştur">
        <livewire:components.form.branch_dropdown wire:key="de33f-kas-{{ Str::random(10) }}" wire:model="branch_id" />
        <x-input label="Adı" wire:model="name" />
        <x-input label="Fiyat" wire:model="price" suffix="₺" money />
        <x-input label="Stok" type="numeric" wire:model.number="stok" />
    </x-slide-over>
</div>
