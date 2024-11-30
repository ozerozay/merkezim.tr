<div>
    <x-slide-over title="Masraf">
        <x-slot:menu>
            <x-button icon="tabler.plus" class="btn-sm btn-primary"
                wire:click="$dispatch('slide-over.open', {component: 'settings.defination.masraf.masraf-create'})" />
        </x-slot:menu>
        @if ($masrafs->isEmpty())
            <p class="text-center">Masraf tipi bulunmuyor.</p>
        @endif
        @foreach ($masrafs as $masraf)
            <x-list-item :item="$masraf" wire:key="ksdv-{{ $masraf->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $masraf->active ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $masraf->name }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $masraf->branch->name }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.defination.masraf.masraf-edit', arguments: {'masraf': {{ $masraf->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$masrafs" />
        </x-slot:actions>
    </x-slide-over>
</div>
