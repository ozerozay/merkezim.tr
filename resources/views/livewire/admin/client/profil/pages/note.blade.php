<?php

use App\Actions\Client\GetClientNotes;
use App\Actions\Note\DeleteNoteAction;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast, WithPagination;

    public $client;

    public $client_notes = null;

    #[Url]
    public $view = 'list';

    #[Url]
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function mount() {}

    public function headers()
    {
        return [
            ['key' => 'date_human', 'label' => 'Tarih', 'sortBy' => 'created_at'],
            ['key' => 'message', 'label' => 'Mesaj', 'sortable' => false],
            ['key' => 'user.name', 'label' => 'Personel', 'sortBy' => 'user_id'],

        ];
    }

    public function getNotes(): LengthAwarePaginator
    {
        return GetClientNotes::run($this->client, true, $this->sortBy);
    }

    public function with()
    {
        return [
            'notes' => $this->getNotes(),
            'headers' => $this->headers(),
        ];
    }

    public function delete($id)
    {
        DeleteNoteAction::run($id);
        $this->success('Not silindi.');
    }

    public function placeholder()
    {
        return view('livewire.components.card.loading.loading');
    }

    public function changeView()
    {
        $this->view = $this->view == 'table' ? 'list' : 'table';
    }
};
?>
<div>
        <div class="flex justify-end mb-4 gap-2">
            <p>Sıralama işlemlerini tablo görünümünden yapabilirsiniz.</p>
            <x-button wire:click="changeView" label="{{ $view == 'table' ? 'LİSTE' : 'TABLO' }}" icon="{{ $view == 'table' ? 'tabler.list' : 'tabler.table' }}" class="btn btn-sm btn-outline" />
        </div>
        @if ($view == 'table')
        <x-card title=" ">
            <x-table :headers="$headers" :rows="$notes" :sort-by="$sortBy" striped with-pagination>
                @can('note_delete')
                @scope('actions', $note)
                <x-button icon="o-trash" responsive wire:click="delete({{ $note->id }})" wire:confirm="Emin misiniz ?" spinner
                    class="btn-sm text-red-600" />
                @endscope
                @endcan
            </x-table>
        </x-card>
        @else
        @foreach ($notes as $note)
        <x-card title="{{ $note->date_human }}" subtitle="{{ $note->user->name ?? '' }}" separator class="mb-2">
            {{ $note->message }}
            <x:slot:menu>
                <x-button icon="o-trash" label="Sil" responsive class="text-red-500" wire:click="delete({{ $note->id }})" spinner />
            </x:slot:menu>
        </x-card>
        @endforeach
        <x-pagination :rows="$notes" />
        @endif
       

</div>