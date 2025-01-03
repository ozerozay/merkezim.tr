<div>
    <x-slide-over title="🏷️ {{ $label->name ?? '' }}" subtitle="Etiket Düzenle">
        <x-toggle label="Aktif" wire:model="active" wire:key="tgvxscb-{{ $label->id }}" />
        <x-input prefix="🏷️" wire:model="name" placeholder="Etiketin adı" />
    </x-slide-over>
</div>
