<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\CreateServiceAction;
use App\Enum\PermissionType;
use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateClientService extends SlideOver
{
    use Toast;

    public int|User $client;

    public $service_ids = [];

    public $sale_id = null;

    public $gender = null;

    public $branch = null;

    public $seans = 1;

    public $gift = false;

    public $message = null;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->branch = $this->client->branch_id;
        $this->gender = $this->client->gender;
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'client_id' => $this->client->id,
            'branch_id' => $this->client->branch_id,
            'service_ids' => $this->service_ids,
            'sale_id' => $this->sale_id,
            'total' => $this->seans,
            'remaining' => $this->seans,
            'gift' => $this->gift,
            'message' => $this->message,
            'user_id' => auth()->user()->id,
            'permission' => PermissionType::action_client_create_service->name,
        ], [
            'client_id' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'service_ids' => 'required|array',
            'sale_id' => 'nullable|exists:sale,id',
            'total' => 'required|integer',
            'remaining' => 'required|integer',
            'gift' => 'required|boolean',
            'message' => 'required',
            'user_id' => 'required',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateServiceAction::run($validator->validated());

        $this->success('Hizmetler eklendi.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-client-service');
    }
}
