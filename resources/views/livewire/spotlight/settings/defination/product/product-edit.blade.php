<div>
    <x-slide-over title="{{ $product->name ?? '' }}" subtitle="Ürün Düzenle">
        <x-toggle label="Aktif" wire:model="active" wire:key="tgvxscb-{{ $product->id }}" />
        <x-input label="Adı" wire:model="name" />
        <x-input label="Fiyat" wire:model="price" suffix="₺" money />
    </x-slide-over>
</div>
