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
        $this->reset('client_id', 'category_id', 'service_ids', 'date', 'service_room_id', 'message');
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
                <x-slot:menu>
                    <x-button label="En yakın tarihi bul" class="btn-primary btn-sm"/>
                </x-slot:menu>
                <div>
                    <div class="flex items-center justify-between w-full">
                        <x-datepicker label="Tarih Aralığı" wire:model="date_find_range"
                                      icon="o-calendar" :config="$date_find_config_range"/>
                        <span>|</span>
                        <x-datepicker label="Birden Fazla Tarih" wire:model="date_find_multi"
                                      icon="o-calendar" :config="$date_find_config_multi"/>
                    </div>
                    <div class="flex space-x-2 mt-5">

                        <x-button label="Tara" class="btn-primary btn-block"/>
                    </div>

                </div>
            @else
                <p>Danışan ve kategori seçin.</p>
            @endif
        </x-card>
    </div>
</div>
