<?php

new class extends \Livewire\Volt\Component {

    public \App\Models\AgendaOccurrence $item;

};

?>
<div>
    <x-card subtitle="{{ $item->agenda->time->format('H:i') }}">
        <x-slot:title>
            <div class="flex flex-auto items-center">
                <x-badge class="{{ $item->agenda->type->color() }}"/>
                <p class="ml-2">{{ $item->agenda->name }}</p>
            </div>
        </x-slot:title>
        <x-slot:menu>
            <x-button icon="{{ $item->agenda->status->icon() }}"
                      class="btn-circle btn-{{ $item->agenda->status->color() }}"/>
        </x-slot:menu>
        {{ $item->agenda->message }}
    </x-card>
</div>
