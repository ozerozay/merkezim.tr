<?php

namespace App\Livewire\Web\Modal;

use App\Models\Appointment;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class RateAppointmentModal extends SlideOver
{
    use Toast;

    public int|Appointment $appointment;

    public $rank = 0;

    public $customAmount;

    public function mount(Appointment $appointment): void
    {
        $this->appointment = $appointment;
    }

    public function save(): void
    {

        $validator = \Validator::make([
            'rank' => $this->rank,
        ], [
            'rank' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

    }

    public function render()
    {
        return view('livewire.client.modal.rate-appointment-modal');
    }
}
