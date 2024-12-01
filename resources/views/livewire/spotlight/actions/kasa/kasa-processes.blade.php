<div>
    <x-slide-over title="{{ $kasa->name ?? '' }}" subtitle="{{ $kasa->branch?->name ?? '' }}">
        @if ($transactions->isEmpty())
            <p class="text-center">İşlem bulunmuyor.</p>
        @else
            <div class="grid grid-cols-2">
                <x-stat title="Gelir" icon="tabler.cash-banknote" color="text-green-500">
                    <x-slot:value>
                        @price($gelir)
                    </x-slot:value>
                </x-stat>
                <x-stat title="Gider" icon="tabler.cash-banknote" color="text-red-500">
                    <x-slot:value>
                        @price($gider)
                    </x-slot:value>
                </x-stat>
            </div>
        @endif

        @foreach ($transactions as $transaction)
            <x-list-item :item="$transaction" wire:key="ksdv-{{ $transaction->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $transaction->price > 0 ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    @price($transaction->price) - {{ $transaction->type->label() }} - {{ $transaction->masraf?->name }}
                </x-slot:value>
                <x-slot:sub-value>
                    <p>{{ $transaction->client?->name }}</p>
                    <p class="text-xs mt-2">{{ $transaction->user->name }} - {{ $transaction->dateHumanCreated }}</p>
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.defination.kasa.kasa-edit', arguments: {'kasa': {{ $kasa->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$transactions" />
        </x-slot:actions>
    </x-slide-over>
</div>
