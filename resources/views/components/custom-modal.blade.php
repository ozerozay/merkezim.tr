@props([
    'actions' => null,
    'body' => null,
    'title' => ''
])
<div aria-modal="true">
    <x-card title="{{ $title }}" subtitle="{{ $subtitle ?? '' }}" separator progress-indicator>
        <x-form wire:submit="save">
            @if ($body)
                {{ $body }}
            @endif
            @if ($actions)
                {{ $actions }}
            @else
                <x-slot:actions>
                    <div class="flex justify-between items-center w-full">
                        <x-button type="button" class="btn-error" wire:click="$dispatch('slide-over.close')"
                                  icon="tabler.x">
                            Kapat
                        </x-button>
                        <x-button type="submit" spinner="save" class="btn-primary" icon="tabler.send">
                            Gönder
                        </x-button>
                    </div>
                </x-slot:actions>
            @endif
        </x-form>
        <x-slot:menu>
            <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('modal.close')"/>
        </x-slot:menu>
    </x-card>

</div>
