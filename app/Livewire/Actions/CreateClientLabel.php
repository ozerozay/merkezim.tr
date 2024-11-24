<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\CreateLabelAction;
use App\Enum\PermissionType;
use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateClientLabel extends SlideOver
{
    use Toast;

    public int|User $client;

    public $client_labels = [];

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->client_labels = $this->client->labels ?? [];

    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-client-label');
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'client_id' => $this->client->id,
            'labels' => $this->client_labels,
            'permission' => PermissionType::action_client_add_label,
            'user_id' => auth()->user()->id,
        ], [
            'client_id' => 'required|exists:users,id',
            'labels' => 'nullable|array',
            'permission' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateLabelAction::run($validator->validated());

        $this->success('Etiketler düzenlendi.');
        $this->close();
    }
}
