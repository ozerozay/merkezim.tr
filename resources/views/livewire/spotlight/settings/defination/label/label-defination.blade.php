<div>
    <x-slide-over title="Danışan Etiketleri">
        <x-slot:menu>
            <x-button icon="tabler.plus" class="btn-sm btn-primary"
                wire:click="$dispatch('slide-over.open', {component: 'settings.defination.label.label-create'})" />
        </x-slot:menu>
        @if ($labels->isEmpty())
            <p class="text-center">Etiket bulunmuyor.</p>
        @endif
        @foreach ($labels as $label)
            <x-list-item :item="$label" wire:key="ksdv-{{ $label->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $label->active ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $label->name }}
                </x-slot:value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.defination.label.label-edit', arguments: {'label': {{ $label->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$labels" />
        </x-slot:actions>
    </x-slide-over>
</div>
