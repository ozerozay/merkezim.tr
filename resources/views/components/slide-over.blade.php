@props([
    'actions' => null,
    'title' => '',
    'subtitle' => '',
    'menu' => null,
])
<div class="overflow-x-hidden">
    <x-card title="{{ $title ?? 'Başlık' }}" subtitle="{{ $subtitle ?? '' }}" separator progress-indicator>
        <x-form wire:submit="save">
            {{ $slot }}
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
            @if ($menu)
                {{ $menu }}
            @else
                <div class="flex items-center gap-2">
                    <x-button wire:key="help-button-{{ Str::random(10) }}"
                             class="btn-sm btn-ghost"
                             @click="showHelp = !showHelp">
                        <x-icon name="o-question-mark-circle" class="w-5 h-5" />
                    </x-button>
                    <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')" />
                </div>
            @endif
        </x-slot:menu>
    </x-card>
</div>
