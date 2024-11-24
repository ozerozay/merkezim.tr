<div>
    <x-slide-over title="Not Al" subtitle="{{ $client->name ?? '' }}">
        <x-textarea label="Mesajınız" wire:model="message" autofocus/>
    </x-slide-over>
</div>
