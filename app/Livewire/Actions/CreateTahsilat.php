<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\CreateTahsilatAction;
use App\Enum\PermissionType;
use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateTahsilat extends SlideOver
{
    use Toast;

    public int|User $client;

    public $kasa_id;

    public $price;

    public $date;

    public function mount(User $client)
    {
        $this->client = $client;
        $this->date = date('Y-m-d');
    }

    public function save()
    {
        $validator = \Validator::make([
            'client_id' => $this->client->id,
            'user_id' => auth()->user()->id,
            'date' => $this->date,
            'kasa_id' => $this->kasa_id,
            'price' => $this->price,
            'permission' => PermissionType::action_client_tahsilat,
        ], [
            'client_id' => 'required',
            'user_id' => 'required',
            'date' => 'required',
            'kasa_id' => 'required',
            'price' => 'required',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateTahsilatAction::run($validator->validated());

        $this->success('Tahsilat alındı.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-tahsilat');
    }
}
