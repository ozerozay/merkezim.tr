<div>
    <x-slide-over title="Hizmet Odaları">
        <x-slot:menu>
            <x-button icon="tabler.plus" class="btn-sm btn-primary"
                wire:click="$dispatch('slide-over.open', {component: 'settings.defination.room.room-create'})" />
        </x-slot:menu>
        @if ($rooms->isEmpty())
            <p class="text-center">Hizmet odası bulunmuyor.</p>
        @endif
        @foreach ($rooms as $room)
            <x-list-item :item="$room" wire:key="cat31-{{ $room->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $room->active ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $room->name }} - {{ $room->branch->name }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $room->category_names() }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.defination.room.room-edit', arguments: {'room': {{ $room->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$rooms" />
        </x-slot:actions>
    </x-slide-over>
</div>
