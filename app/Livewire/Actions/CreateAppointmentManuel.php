<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\CalculateClientServicesDuration;
use App\Actions\Spotlight\Actions\Client\CreateAppointmentManuelAction;
use App\Enum\PermissionType;
use App\Models\ServiceCategory;
use App\Models\User;
use Livewire\Attributes\Computed;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateAppointmentManuel extends SlideOver
{
    use Toast;

    public int|User $client;

    public int|ServiceCategory $category;

    public $service_ids = [];

    public $date;

    public $room_id;

    public $message;

    public function mount(User $client, ServiceCategory $category): void
    {
        $this->client = $client;
        $this->category = $category;
    }

    #[Computed]
    public function calculateDuration()
    {
        return CalculateClientServicesDuration::run($this->service_ids);
    }

    public function save(): void
    {
        $validator = \Validator::make(
            [
                'client_id' => $this->client->id,
                'category_id' => $this->category->id,
                'service_ids' => $this->service_ids,
                'date' => $this->date,
                'room_id' => $this->room_id,
                'message' => $this->message,
                'user_id' => auth()->user()->id,
                'permission' => PermissionType::action_client_create_appointment,
            ], [
                'client_id' => 'required|exists:users,id',
                'category_id' => 'required|exists:service_categories,id',
                'service_ids' => 'required|array',
                'date' => 'required|date|after:now',
                'room_id' => 'required|exists:service_rooms,id',
                'message' => 'required',
                'user_id' => 'required|exists:users,id',
                'permission' => 'required',
            ]
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateAppointmentManuelAction::run($validator->validated());

        $this->close();
        $this->success('Randevu oluşturuldu.');
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-appointment-manuel');
    }
}
