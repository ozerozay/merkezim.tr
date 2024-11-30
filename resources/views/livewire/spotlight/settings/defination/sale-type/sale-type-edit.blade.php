<div>
    <x-slide-over title="{{ $type->name ?? '' }}" subtitle="Satış Tipi Düzenle">
        <x-toggle label="Aktif" wire:model="active" wire:key="tgvxscb-{{ $type->id }}" />
        <x-input label="Adı" wire:model="name" />
    </x-slide-over>
</div>
