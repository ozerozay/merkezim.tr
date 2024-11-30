<div>
    <x-slide-over title="Kasa">
        <x-slot:menu>
            <x-button icon="tabler.plus" class="btn-sm btn-primary"
                wire:click="$dispatch('slide-over.open', {component: 'settings.defination.kasa.kasa-create'})" />
        </x-slot:menu>
        @if ($kasas->isEmpty())
            <p class="text-center">Kasa bulunmuyor.</p>
        @endif
        @foreach ($kasas as $kasa)
            <x-list-item :item="$kasa" wire:key="ksdv-{{ $kasa->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $kasa->active ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $kasa->name }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $kasa->branch->name }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.defination.kasa.kasa-edit', arguments: {'kasa': {{ $kasa->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$kasas" />
        </x-slot:actions>
    </x-slide-over>
</div>
