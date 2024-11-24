<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\CreateNoteAction;
use App\Enum\PermissionType;
use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateClientNote extends SlideOver
{
    use Toast;

    public int|User $client;

    public ?string $message = null;

    public function mount(User $client): void
    {
        $this->client = $client;
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create_note');
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'client_id' => $this->client->id,
            'message' => $this->message,
            'permission' => PermissionType::action_client_add_note->name,
            'user_id' => auth()->user()->id,
        ], [
            'client_id' => 'required|exists:users,id',
            'message' => 'required',
            'permission' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateNoteAction::run($validator->validated());

        $this->success('Not oluşturuldu.');
        $this->close();

    }
}
