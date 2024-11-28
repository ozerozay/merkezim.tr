<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Check\CheckAvailableAppointments;
use App\Actions\Spotlight\Actions\Client\CalculateClientServicesDuration;
use App\Actions\Spotlight\Actions\Client\CreateAppointmentManuelAction;
use App\Enum\PermissionType;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Peren;
use Livewire\Attributes\Computed;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateAppointmentRange extends SlideOver
{
    use Toast;

    public int|User $client;

    public int|ServiceCategory $category;

    public $service_ids = [];

    public $date_find_range;

    public $message;

    public $available_appointments_range = [];

    public $selected_available_appointments_range = null;

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

    public function findAvailableAppointmentsRange(): void
    {
        try {
            if (empty($this->service_ids)) {
                $this->error('Hizmet seçmelisiniz.');

                return;
            }
            if (! $this->date_find_range) {
                $this->error('Tarih seçmelisiniz.');

                return;
            }
            $duration = CalculateClientServicesDuration::run($this->service_ids);

            if ($duration == 0) {
                $this->error('Süre hesaplanamadı.');

                return;
            }

            $client = \App\Models\User::query()->where('id', $this->client->id)->first();

            $format_range = Peren::formatRangeDate($this->date_find_range);

            $info = [
                'branch_id' => $client->branch_id,
                'search_date_first' => $format_range['first_date'],
                'search_date_last' => $format_range['last_date'],
                'category_id' => $this->category->id,
                'duration' => $duration,
                'type' => 'range',
            ];

            $available_appointments_range = CheckAvailableAppointments::run($info);

            $toSelect = [];

            foreach ($available_appointments_range as $key => $dates) {
                foreach ($dates as $rangeDate) {
                    $title = \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y').' - '.$rangeDate['name'];
                    foreach ($rangeDate['gaps'] as $gap) {
                        $toSelect[$title][] = [
                            'id' => \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y').'||'.$rangeDate['name'].'||'.$gap,
                            'name' => $gap,
                        ];
                    }
                }

            }
            $this->available_appointments_range = $toSelect;
        } catch (\Throwable $e) {
            $this->error('Lütfen daha sonra tekrar deneyin.'.$e->getMessage());
        }
    }

    public function save(): void
    {
        try {
            if (! $this->selected_available_appointments_range) {
                $this->error('Tarih seçin.');

                return;
            }
            $value = explode('||', $this->selected_available_appointments_range);

            $service_room_id = \App\Models\ServiceRoom::where('name', $value[1])->where('branch_id', $this->client->branch_id)->first()->id;
            $time_split = explode('-', $value[2]);
            $range_date = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $value[0].' '.$time_split[0])->format('Y-m-d H:i');

            $validator = \Validator::make(
                [
                    'client_id' => $this->client->id,
                    'category_id' => $this->category->id,
                    'service_ids' => $this->service_ids,
                    'date' => $range_date,
                    'room_id' => $service_room_id,
                    'message' => $this->message,
                    'user_id' => auth()->user()->id,
                    'permission' => PermissionType::action_client_create_appointment->name,
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

            $this->success('Randevu oluşturuldu.');
            $this->close();
        } catch (\Throwable $e) {
            $this->error('Lütfen, tekrar deneyin.');
        }

    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-appointment-range');
    }
}
