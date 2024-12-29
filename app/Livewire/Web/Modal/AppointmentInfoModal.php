<?php

namespace App\Livewire\Web\Modal;

use App\AppointmentStatus;
use App\Enum\SettingsType;
use App\Models\Appointment;
use App\Traits\WebSettingsHandler;
use Carbon\Carbon;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class AppointmentInfoModal extends SlideOver
{
    use Toast, WebSettingsHandler;

    public int|Appointment $appointment;

    public $remaining_text = '';

    public $can_cancel = false;

    public function mount(Appointment $appointment): void
    {
        $this->appointment = $appointment;
        $this->getSettings();

        $cancel_time = $this->getInt(SettingsType::client_page_appointment_cancel_time->name);

        $this->remaining_text = Carbon::now()->diffForHumans($this->appointment->date);

        $remaining_time = Carbon::now()->diffInMinutes($this->appointment->date, false);

        $this->can_cancel = AppointmentStatus::activeNoLate()->contains($this->appointment->status) && ! ($cancel_time == 0) && ! ($this->can_cancel > $remaining_time);
    }

    public function render()
    {
        return view('livewire.client.modal.appointment-info-modal');
    }
}
