<?php

use App\Actions\Appointment\CreateManuelAppointmentAction;
use App\Actions\User\CheckUserInstantApprove;
use App\Actions\User\CreateApproveRequestAction;
use App\ApproveTypes;
use App\Peren;

new
#[\Livewire\Attributes\Title('Randevu Oluştur')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    #[\Livewire\Attributes\Url(as: 'client')]
    public ?int $client_id = null;

    public ?int $category_id;

    public ?int $service_room_id = null;

    public ?array $service_ids = [];

    public array $date_config = [];

    public array $date_find_config_range = [];

    public array $date_find_config_multi = [];

    public ?string $date = null;

    public ?string $date_find_range;

    public ?string $date_find_multi;

    public ?string $selectedTab = 'create';

    public string $message = '';

    public array $available_appointments_range = [];

    public ?string $selected_available_appointments_range = null;

    public function mount(): void
    {
        $this->date_config = \App\Peren::dateConfig(min: \Carbon\Carbon::now()->format('Y-m-d'), enableTime: true);
        $this->date_find_config_range = \App\Peren::dateConfig(min: \Carbon\Carbon::now()->format('Y-m-d'), mode: 'range');
        $this->date_find_config_multi = \App\Peren::dateConfig(min: \Carbon\Carbon::now()->format('Y-m-d'), mode: 'multiple');
    }

    public function createAppointmentManuel(): void
    {
        $validator = \Illuminate\Support\Facades\Validator::make(
            [
                'client_id' => $this->client_id,
                'category_id' => $this->category_id,
                'service_ids' => $this->service_ids,
                'date' => $this->date,
                'room_id' => $this->service_room_id,
                'message' => $this->message,
            ], [
                'client_id' => 'required|exists:users,id',
                'category_id' => 'required|exists:service_categories,id',
                'service_ids' => 'required|array',
                'date' => 'required|date|after:now',
                'room_id' => 'required|exists:service_rooms,id',
                'message' => 'required',
                'user_id' => 'required|exists:users,id',
            ]
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\User\CheckClientBranchAction::run($this->client_id);

        CreateManuelAppointmentAction::run($validator->validated());

        $this->success('Randevu oluşturuldu.');
        $this->reset('client_id', 'category_id', 'service_ids', 'date', 'service_room_id', 'message', 'available_appointments_range', 'selected_available_appointments_range');
    }

    public function findAvailableAppointmentsMultiple(): void
    {
        if (empty($this->service_ids)) {
            $this->error('Hizmet seçmelisiniz.');
            return;
        }
        $duration = \App\Actions\Client\CalculateClientServicesDuration::run($this->service_ids);

        if ($duration == 0) {
            $this->error('Süre hesaplanamadı.');

            return;
        }

        $client = \App\Models\User::query()->where('id', $this->client_id)->first();

        $dates = collect(explode(',', $this->date_find_multi))->map(function ($q) {
            $q = trim($q);
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $q);
            return $date->format('Y-m-d');
        });

        $info_multiple = [
            'branch_id' => $client->branch_id,
            'category_id' => $this->category_id,
            'duration' => $duration,
            'type' => 'multiple',
            'dates' => $dates->toArray()
        ];
        $available_appointments_range = \App\Actions\Appointment\CheckAvailableAppointments::run($info_multiple);

        $toSelect = [];

        foreach ($available_appointments_range as $key => $dates) {
            foreach ($dates as $rangeDate) {
                $title = \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y') . ' - ' . $rangeDate['name'];
                foreach ($rangeDate['gaps'] as $gap) {
                    $toSelect[$title][] = [
                        'id' => \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y') . '||' . $rangeDate['name'] . '||' . $gap,
                        'name' => $gap
                    ];
                }
            }

        }
        $this->available_appointments_range = $toSelect;
    }

    public function findAvailableAppointmentsRange(): void
    {
        try {
            if (empty($this->service_ids)) {
                $this->error('Hizmet seçmelisiniz.');
                return;
            }
            $duration = \App\Actions\Client\CalculateClientServicesDuration::run($this->service_ids);

            if ($duration == 0) {
                $this->error('Süre hesaplanamadı.');

                return;
            }

            $client = \App\Models\User::query()->where('id', $this->client_id)->first();

            $format_range = Peren::formatRangeDate($this->date_find_range);

            $info = [
                'branch_id' => $client->branch_id,
                'search_date_first' => $format_range['first_date'],
                'search_date_last' => $format_range['last_date'],
                'category_id' => $this->category_id,
                'duration' => $duration,
                'type' => 'range',
            ];

            $available_appointments_range = \App\Actions\Appointment\CheckAvailableAppointments::run($info);

            $toSelect = [];

            foreach ($available_appointments_range as $key => $dates) {
                foreach ($dates as $rangeDate) {
                    $title = \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y') . ' - ' . $rangeDate['name'];
                    foreach ($rangeDate['gaps'] as $gap) {
                        $toSelect[$title][] = [
                            'id' => \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y') . '||' . $rangeDate['name'] . '||' . $gap,
                            'name' => $gap
                        ];
                    }
                }

            }
            $this->available_appointments_range = $toSelect;
        } catch (\Throwable $e) {
            $this->error('Lütfen daha sonra tekrar deneyin.' . $e->getMessage());
        }
    }

    public function createRangeAppointment(): void
    {
        try {
            if (!$this->selected_available_appointments_range) {
                $this->error('Tarih seçin');
                return;
            }

            $value = explode('||', $this->selected_available_appointments_range);

            $client = \App\Models\User::query()->where('id', $this->client_id)->first();

            $service_room_id = \App\Models\ServiceRoom::where('name', $value[1])->where('branch_id', $client->branch_id)->first()->id;
            $time_split = explode('-', $value[2]);
            $range_date = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $value[0] . ' ' . $time_split[0])->format('Y-m-d H:i');

            $validator = \Illuminate\Support\Facades\Validator::make(
                [
                    'client_id' => $this->client_id,
                    'category_id' => $this->category_id,
                    'service_ids' => $this->service_ids,
                    'date' => $range_date,
                    'room_id' => $service_room_id,
                    'message' => $this->message,
                    'user_id' => auth()->user()->id,
                ], [
                    'client_id' => 'required|exists:users,id',
                    'category_id' => 'required|exists:service_categories,id',
                    'service_ids' => 'required|array',
                    'date' => 'required|date|after:now',
                    'room_id' => 'required|exists:service_rooms,id',
                    'message' => 'required',
                    'user_id' => 'required|exists:users,id',
                ]
            );

            if ($validator->fails()) {
                $this->error($validator->messages()->first());
                return;
            }

            \App\Actions\User\CheckClientBranchAction::run($this->client_id);

            CreateManuelAppointmentAction::run($validator->validated());

            $this->success('Randevu oluşturuldu.');
            $this->reset('client_id', 'category_id', 'service_ids', 'date', 'service_room_id', 'message', 'available_appointments_range', 'selected_available_appointments_range');
            //$this->success($this->selected_available_appointments_range);
        } catch (\Throwable $e) {
            $this->error('Lütfen daha sonra tekrar deneyin.' . $e->getMessage());
        }

    }
};

?>
<div>
    <x-header title="Randevu Oluştur" separator progress-indicator/>
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2">
        <x-card title="Oluştur" separator>
            <x-form wire:submit="createAppointmentManuel">
                <livewire:components.form.client_dropdown wire:model.live="client_id"/>
                @if ($client_id)
                    <livewire:components.client.client_service_category_dropdown
                        wire:model.live="category_id"
                        :client_id="$client_id"/>
                    @if ($category_id)
                        <livewire:components.client.client_service_multi_dropdown
                            wire:model.live.debounce.1000ms="service_ids"
                            :client_id="$client_id"
                            :category_id="$category_id"/>
                        @if ($service_ids)
                            <x-datepicker label="Tarih" wire:model="date"
                                          icon="o-calendar" :config="$date_config"/>
                            <livewire:components.client.client_service_room_dropdown
                                wire:model="service_room_id"
                                :client_id="$client_id"
                                :category_id="$category_id"/>
                            <x-input wire:model="message" label="Randevu notunuz"/>
                            <x-button label="Randevu Oluştur" type="submit" spinner="createAppointmentManuel"
                                      class="btn-primary btn-block mt-5"/>
                        @endif
                    @endif
                @endif
                <x-slot:actions>
                    <div class="flex">
                        @if ($date)

                        @endif
                    </div>
                </x-slot:actions>
            </x-form>
        </x-card>
        <x-card title="Tara" separator>
            @if ($service_ids)
                <div>
                    <div class="flex items-center justify-between w-full">
                        <div>
                            <x-form wire:submit="findAvailableAppointmentsRange">
                                <x-datepicker label="Tarih Aralığı" wire:model="date_find_range"
                                              icon="o-calendar" :config="$date_find_config_range"/>
                                <x-button label="Tara" type="submit" class="btn-primary btn-block mt-5"/>
                            </x-form>
                        </div>
                        <div>
                            <x-form wire:submit="findAvailableAppointmentsMultiple">
                                <x-datepicker label="Birden Fazla Tarih" wire:model="date_find_multi"
                                              icon="o-calendar" :config="$date_find_config_multi"/>
                                <x-button label="Tara" type="submit" class="btn-primary btn-block  mt-5"/>
                            </x-form>
                        </div>
                    </div>
                    @if (count($available_appointments_range) > 0)
                        <x-hr/>
                        <x-form>
                            <x-select-group label="Uygun randevu tarihleri" :options="$available_appointments_range"
                                            wire:model="selected_available_appointments_range"/>
                            <x-input wire:model="message" label="Randevu notunuz"/>
                            <x-button label="Randevu Oluştur" wire:click="createRangeAppointment"
                                      class="btn-primary btn-block  mt-5"/>
                        </x-form>
                    @endif
                </div>
            @else
                <p>Danışan, hizmet kategorisi ve hizmet seçin.</p>
            @endif
        </x-card>
    </div>

</div>
