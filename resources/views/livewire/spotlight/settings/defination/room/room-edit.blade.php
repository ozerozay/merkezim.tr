<div>
    <x-slide-over title="{{ $room->name ?? '' }}" subtitle="Oda Düzenle">
        <x-toggle label="Aktif" wire:model="active" wire:key="tgvxscb-{{ $room->id }}" />
        <x-input label="Adı" wire:model="name" />
        <livewire:components.form.category_multi_dropdown wire:key="de33ssf-kas-{{ Str::random(10) }}"
            wire:model="category_ids" />
    </x-slide-over>
</div>
