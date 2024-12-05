<x-client-profil-modal title="Hizmetler" subtitle="{{ $client->name ?? '' }}">
    <x-slot:body>
        @if ($services->isEmpty())
            <p class="text-center">Hizmet bulunmuyor.</p>
        @endif
        @foreach ($services as $service)
            <x-list-item :item="$service" class="cursor-pointer bg-base-200 mb-1"
                wire:click="$dispatch('slide-over.open', {component: 'modals.appointment.appointment-modal', arguments: {'appointment': {{ $service->id }}}})">
                <x-slot:avatar>
                    <x-badge value="{{ $service->total }}" class="badge-{{ $service->status->color() }}" />
                    <br />
                    <x-badge value="{{ $service->remaining }}" class="badge-{{ $service->status->color() }}" />
                    <br />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $service->service->name }} ({{ $service->sale->sale_no }})
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $service->date }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-badge value="{{ $service->status->label() }}" class="badge-{{ $service->status->color() }}" />
                </x-slot:actions>
            </x-list-item>
            @if (1 == 2)
                <x-card title="{{ $service->service->name }}" subtitle="{{ $service->status->label() ?? '' }}"
                    separator class="mb-2 bg-base-200">
                    <x:slot:menu>
                        <x-button icon="o-trash" label="Sil" responsive class="text-red-500"
                            wire:click="delete({{ $service->id }})" wire:confirm="Emin misiniz ?" spinner />
                    </x:slot:menu>
                </x-card>
            @endif
        @endforeach
        <x-pagination :rows="$services" />
    </x-slot:body>
</x-client-profil-modal>
