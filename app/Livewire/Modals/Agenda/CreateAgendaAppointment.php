<?php

namespace App\Livewire\Modals\Agenda;

use Carbon\Carbon;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateAgendaAppointment extends SlideOver
{
    use Toast;

    public $client = null;

    public $date = null;

    public $message;

    public $name;

    public $branch;

    public $type = 'reminder';

    public function mount(): void
    {
        $this->date = date('Y-m-d H:i');
    }

    public array $typeList = [
        [
            'id' => 'reminder',
            'name' => 'Hatırlatma',
        ],
        [
            'id' => 'appointment',
            'name' => 'Randevu',
        ],

    ];

    public function save()
    {
        $validator = \Illuminate\Support\Facades\Validator::make([
            'client_id' => $this->client,
            'name' => $this->name,
            'type' => $this->type,
            'date' => Carbon::createFromFormat('Y-m-d H:i', $this->date)->format('Y-m-d'),
            'date_create' => Carbon::createFromFormat('Y-m-d H:i', $this->date)->format('Y-m-d'),
            'time' => Carbon::createFromFormat('Y-m-d H:i', $this->date)->format('H:i:s'),
            'message' => $this->message,
            'branch_id' => $this->branch,
            'user_id' => auth()->user()->id,
        ], [
            'client_id' => 'nullable|exists:users,id',
            'name' => 'required',
            'type' => 'required',
            'date' => 'required',
            'date_create' => 'required',
            'time' => 'required',
            'message' => 'required',
            'branch_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        \App\Actions\Agenda\CreateReminderAction::run($validator->validated());

        $this->success('Oluşturuldu.');
        $this->dispatch('refresh-agendas');
        $this->reset('client', 'name', 'type', 'date', 'message', 'branch');

    }

    public function render()
    {
        return view('livewire.spotlight.modals.agenda.create-agenda-appointment');
    }
}
