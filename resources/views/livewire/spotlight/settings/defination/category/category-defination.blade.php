<div>
    <x-slide-over title="Hizmet Kategorileri">
        <x-slot:menu>
            <x-button icon="tabler.plus" class="btn-sm btn-primary"
                wire:click="$dispatch('slide-over.open', {component: 'settings.defination.category.category-create'})" />
        </x-slot:menu>
        @if ($categories->isEmpty())
            <p class="text-center">Kategori bulunmuyor.</p>
        @endif
        @foreach ($categories as $category)
            <x-list-item :item="$category" wire:key="cat31-{{ $category->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $category->active ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $category->name }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $category->branch_names() }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.defination.category.category-edit', arguments: {'category': {{ $category->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$categories" />
        </x-slot:actions>
    </x-slide-over>
</div>
