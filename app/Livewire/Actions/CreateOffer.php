<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\CreateOfferAction;
use App\Enum\PermissionType;
use App\Models\User;
use App\Traits\ServicePackageProductHandler;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateOffer extends SlideOver
{
    use ServicePackageProductHandler, Toast;

    public int|User $client;

    public $price = 0.0;

    public $expire_date;

    public $message;

    public $month = 0;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->selected_services = collect();
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'client_id' => $this->client->id,
            'price' => $this->price,
            'expire_date' => $this->expire_date,
            'message' => $this->message,
            'month' => $this->month,
            'services' => $this->selected_services->toArray(),
            'user_id' => auth()->user()->id,
            'permission' => PermissionType::action_client_create_offer,
        ], [
            'client_id' => 'required|exists:users,id',
            'price' => 'required|decimal:0,2|min:1',
            'expire_date' => 'nullable|after:today',
            'message' => 'required',
            'month' => 'required',
            'services' => 'required|array|min:1',
            'user_id' => 'required|exists:users,id',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateOfferAction::run($validator->validated());

        $this->success('Teklif oluşturuldu.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-offer');
    }
}
