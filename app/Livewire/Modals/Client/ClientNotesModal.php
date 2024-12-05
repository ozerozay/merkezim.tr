<?php

namespace App\Livewire\Modals\Client;

use App\Actions\Spotlight\Actions\Client\Commands\DeleteClientNoteAction;
use App\Actions\Spotlight\Actions\Client\Get\GetClientNotesAction;
use App\Enum\PermissionType;
use App\Models\User;
use App\Traits\ClientProfilModalHandler;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ClientNotesModal extends SlideOver
{
    use ClientProfilModalHandler, Toast, WithoutUrlPagination, WithPagination;

    public int|User $client;

    public function mount(User $client): void
    {
        $this->client = $client;
    }

    public function delete($id)
    {
        DeleteClientNoteAction::run([
            'user_id' => auth()->user()->id,
            'permission' => PermissionType::note_process,
            'id' => $id,
        ]);

        $this->success('Not silindi.');
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.client-notes-modal', [
            'notes' => GetClientNotesAction::run($this->client->id, true),
        ]);
    }
}
