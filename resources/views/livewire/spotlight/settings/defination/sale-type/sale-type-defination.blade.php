<div>
    <x-slide-over title="Satış Tipleri">
        <x-slot:menu>
            <x-button icon="tabler.plus" class="btn-sm btn-primary"
                wire:click="$dispatch('slide-over.open', {component: 'settings.defination.sale-type.sale-type-create'})" />
        </x-slot:menu>
        @if ($sale_types->isEmpty())
            <p class="text-center">Satış tipi bulunmuyor.</p>
        @endif
        @foreach ($sale_types as $type)
            <x-list-item :item="$type" wire:key="ksdv-{{ $type->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $type->active ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $type->name }}
                </x-slot:value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.defination.sale-type.sale-type-edit', arguments: {'type': {{ $type->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$sale_types" />
        </x-slot:actions>
    </x-slide-over>
</div>
