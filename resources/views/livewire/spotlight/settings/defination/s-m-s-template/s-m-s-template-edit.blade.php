<div>
    <x-slide-over title="{{ $template->name ?? '' }}" subtitle="Şablon Düzenle">
        <x-toggle label="Aktif" wire:model="active" wire:key="tgvxscb-{{ $template->id }}" />
        <x-input label="Adı" wire:model="name" />
        <x-textarea label="Mesaj" wire:model="message" />
    </x-slide-over>
</div>
