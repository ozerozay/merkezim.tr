<?php

new
#[\Livewire\Attributes\Title('Randevu Ekranı Kapat')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    public $branch = null;

    public $room_id = null;

    public $date;
    public $time;

    public $date_config;
    public $date_config_time;

    public $message;

    public function updatedBranch($branch): void
    {
        $this->dispatch('reload-branch-service-rooms', $branch)->to('components.form.service_room_dropdown');
    }

    public function mount(): void
    {
        $this->date_config = \App\Peren::dateConfig(min: date('Y-m-d'), enableTime: true);
        $this->date_config_time = \App\Peren::dateConfig(min: date('Y-m-d'), enableTime: true);
    }

    public function save(): void
    {
        $validator = Validator::make([
            'branch_id' => $this->branch,
            'service_room_id' => $this->room_id,
            'start_date' => $this->date,
            'end_date' => $this->time,
            'message' => $this->message,
            'user_id' => auth()->user()->id
        ], [
            'branch_id' => 'required',
            'service_room_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'message' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        if (\App\Actions\User\CheckUserInstantApprove::run(auth()->user()->id)) {
            \App\Actions\Appointment\CloseAppointmentAction::run($validator->validated());

            $this->success('Randevu ekranı kapatıldı.');
        } else {
            \App\Actions\User\CreateApproveRequestAction::run($validator->validated(), auth()->user()->id, \App\ApproveTypes::close_appointment, $this->message);

            $this->success(\App\Peren::$approve_request_ok);
        }

        $this->reset('branch', 'room_id', 'message');
    }
};

?>

<div>
    <x-card title="Randevu Ekranı Kapat" progress-indicator separator>
        <x-form wire:submit="save">
            <livewire:components.form.branch_dropdown wire:model.live="branch"/>
            @if ($branch)
                <livewire:components.form.service_room_dropdown wire:model="room_id" :branch_id="$branch"/>
                <x-datepicker label="Tarih ve Başlangıç Saati" wire:model="date"
                              icon="o-calendar" :config="$date_config"/>
                <x-datepicker label="Bitiş Saati" wire:model="time"
                              icon="o-calendar" :config="$date_config_time"/>
                <x-input wire:model="message" label="Açıklama"/>
            @endif
            <x:slot:actions>
                <x-button label="Gönder" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary"/>
            </x:slot:actions>
        </x-form>
    </x-card>
</div>
