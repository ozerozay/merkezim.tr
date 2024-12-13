<?php

namespace App\Livewire\Modals\Agenda;

use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateAgendaPaymentTracking extends SlideOver
{
    use Toast;

    public $client = null;

    public $date = null;

    public $name;

    public $price = 0;

    public $frequency = 'tek';

    public $installment = 1;

    public $branch;

    public $message;

    public array $frequencyList = [
        [
            'id' => 'tek',
            'name' => 'Tek',
        ],
        [
            'id' => 'gun',
            'name' => 'Günlük',
        ],
        [
            'id' => 'hafta',
            'name' => 'Haftalık',
        ],
        [
            'id' => 'ay',
            'name' => 'Aylık',
        ],
    ];

    public function mount(): void
    {
        $this->date = date('Y-m-d');
    }

    public function save(): void
    {
        $validator = \Validator::make(
            [
                'branch_id' => $this->branch,
                'frequency' => $this->frequency,
                'name' => $this->name,
                'date' => $this->date,
                'date_create' => $this->date,
                'price' => $this->price,
                'type' => 'payment',
                'user_id' => auth()->user()->id,
                'installment' => $this->installment,
                'message' => $this->message,
            ],
            [
                'branch_id' => 'required',
                'frequency' => 'required',
                'name' => 'required',
                'date' => 'required',
                'date_create' => 'required',
                'type' => 'required',
                'price' => 'nullable|decimal:0,2',
                'user_id' => 'required|exists:users,id',
                'installment' => 'nullable',
                'message' => 'required',
            ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        \App\Actions\Agenda\CreatePaymentTrackingAction::run($validator->validated());

        $this->success('Oluşturuldu.');
        $this->dispatch('refresh-agendas');
        $this->reset('branch', 'name', 'frequency', 'date', 'price');

    }

    public function render()
    {
        return view('livewire.spotlight.modals.agenda.create-agenda-payment-tracking');
    }
}
