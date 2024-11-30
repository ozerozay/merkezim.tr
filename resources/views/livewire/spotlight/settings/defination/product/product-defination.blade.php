<div>
    <x-slide-over title="Ürün">
        <x-slot:menu>
            <x-button icon="tabler.plus" class="btn-sm btn-primary"
                wire:click="$dispatch('slide-over.open', {component: 'settings.defination.product.product-create'})" />
        </x-slot:menu>
        @if ($products->isEmpty())
            <p class="text-center">Ürün bulunmuyor.</p>
        @endif
        @foreach ($products as $product)
            <x-list-item :item="$product" wire:key="ksdv-{{ $product->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $product->active ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $product->name }} - @price($product->price)
                </x-slot:value>
                <x-slot:sub-value>
                    Stok: {{ $product->stok }} - {{ $product->branch->name }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.defination.product.product-edit', arguments: {'product': {{ $product->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$products" />
        </x-slot:actions>
    </x-slide-over>
</div>
