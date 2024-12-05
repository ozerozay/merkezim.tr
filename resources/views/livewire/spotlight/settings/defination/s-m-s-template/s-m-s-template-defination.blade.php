<div>
    <x-slide-over title="SMS Şablonları">
        <x-slot:menu>
            <x-button icon="tabler.plus" class="btn-sm btn-primary"
                wire:click="$dispatch('slide-over.open', {component: 'settings.defination.s-m-s-template.s-m-s-template-create'})" />
        </x-slot:menu>
        @if ($templates->isEmpty())
            <p class="text-center">SMS şablonu bulunmuyor.</p>
        @endif
        @foreach ($templates as $template)
            <x-list-item :item="$template" wire:key="ksdv-{{ $template->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $template->active ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $template->name }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $template->branch->name }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.defination.s-m-s-template.s-m-s-template-edit', arguments: {'template': {{ $template->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$templates" />
        </x-slot:actions>
    </x-slide-over>
</div>
