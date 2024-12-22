<?php

new class extends \Livewire\Volt\Component {

    public \App\Models\AgendaOccurrence $item;

};

?>
<div>
    <x-card subtitle="{{ $item->agenda->user->name }}">
        <x-slot:title>
            <div class="flex flex-auto items-center">
                <x-badge class="{{ $item->agenda->type->color() }}"/>
                <p class="ml-2">{{ $item->agenda->name }}</p>
            </div>
        </x-slot:title>
        <x-slot:menu>
            <x-button icon="{{ $item->status->icon() }}"
                      wire:click="$dispatch('slide-over.open', {'component': 'modals.agenda.update-agenda-status', 'arguments' : {'occurrence': {{ $item->id }}}})"
                      class="btn-circle btn-sm btn-{{ $item->status->color() }}"/>
        </x-slot:menu>
        <p class="break-all">{{ $item->agenda->message }}</p>

    </x-card>
</div>
