<div>
    <x-slide-over title="{{ $masraf->name ?? '' }}" subtitle="Masraf Düzenle">
        <x-toggle label="Aktif" wire:model="active" wire:key="tgvxscb-{{ $masraf->id }}" />
        <x-input label="Adı" wire:model="name" />
    </x-slide-over>
</div>
