<x-client-profil-modal title="Notlar" subtitle="{{ $client->name ?? '' }}">
    <x-slot:body>
        @if ($notes->count() == 0)
            <p class="text-center">Not bulunmuyor.</p>
        @endif
        @foreach ($notes as $note)
            <x-card title="{{ $note->date_human }}" subtitle="{{ $note->user->name ?? '' }}" separator
                class="mb-2 bg-base-200">
                {{ $note->message }}
                @can(\App\Enum\PermissionType::note_process)
                    <x:slot:menu>
                        <x-button icon="o-trash" label="Sil" responsive class="text-red-500"
                            wire:click="delete({{ $note->id }})" wire:confirm="Emin misiniz ?" spinner />
                    </x:slot:menu>
                @endcan
            </x-card>
        @endforeach
        <x-pagination :rows="$notes" />
    </x-slot:body>
</x-client-profil-modal>
