<div>
    <x-slide-over title="Mağaza - Hizmetler">
        <x-slot:menu>
            <x-button icon="tabler.plus" class="btn-sm btn-primary"
                wire:click="$dispatch('slide-over.open', {component: 'settings.shop.shop-service-create'})" />
        </x-slot:menu>
        @if ($services->isEmpty())
            <p class="text-center">Hizmet bulunmuyor.</p>
        @endif
        @foreach ($services as $service)
            <x-list-item :item="$service" wire:key="adv-{{ $service->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $service->active ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $service->name }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $service->branch->name }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.shop.shop-service-edit', arguments: {'service': {{ $service->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$services" />
        </x-slot:actions>
    </x-slide-over>
</div>
