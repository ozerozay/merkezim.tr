    <x-card title="Onay" separator progress-indicator>
        @foreach ($approves as $approve)
            @livewire('spotlight.components.cards.approve.card_approve_' . $approve->type->name, ['approve' => $approve], key($approve->id))
        @endforeach
        <x-pagination :rows="$approves" />
        <x-slot:menu>
            <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')" />
        </x-slot:menu>
    </x-card>
