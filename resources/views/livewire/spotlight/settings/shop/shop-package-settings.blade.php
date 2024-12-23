<div>
    <x-slide-over title="Mağaza - Paketler">
        <x-slot:menu>
            <x-button icon="tabler.plus" class="btn-sm btn-primary"
                wire:click="$dispatch('slide-over.open', {component: 'settings.shop.shop-package-settings-create'})" />
        </x-slot:menu>
        @if ($packages->isEmpty())
            <p class="text-center">Paket bulunmuyor.</p>
        @endif
        @foreach ($packages as $package)
            <x-list-item :item="$package" wire:key="ksdv-{{ $package->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $package->active ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $package->name }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $package->package->branch->name }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.shop.shop-package-settings-edit', arguments: {'package': {{ $package->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$packages" />
        </x-slot:actions>
    </x-slide-over>
</div>