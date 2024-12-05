@props([
    'actions' => null,
    'body' => null,
    'title' => '',
    'subtitle' => '',
])
<div aria-modal="true">
    <x-card title="{{ $title }}" subtitle="{{ $subtitle ?? '' }}" separator progress-indicator>
        @if ($body)
            {{ $body }}
        @endif
        @if ($actions)
            {{ $actions }}
        @endif
        <x-slot:menu>
            <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="closeAndEmit" />
        </x-slot:menu>
    </x-card>

</div>
