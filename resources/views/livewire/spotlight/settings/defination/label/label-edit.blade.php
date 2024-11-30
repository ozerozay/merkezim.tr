<div>
    <x-slide-over title="{{ $label->name ?? '' }}" subtitle="Etiket Düzenle">
        <x-toggle label="Aktif" wire:model="active" wire:key="tgvxscb-{{ $label->id }}" />
        <x-input label="Adı" wire:model="name" />
    </x-slide-over>
</div>
