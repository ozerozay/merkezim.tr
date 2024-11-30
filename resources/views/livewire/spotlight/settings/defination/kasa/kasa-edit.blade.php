<div>
    <x-slide-over title="{{ $kasa->name ?? '' }}" subtitle="Kasa Düzenle">
        <x-toggle label="Aktif" wire:model="active" wire:key="tgvxscb-{{ $kasa->id }}" />
        <x-input label="Adı" wire:model="name" />
    </x-slide-over>
</div>
