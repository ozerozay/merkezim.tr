<?php

namespace App\Livewire\Web\Modal;

use App\AppointmentStatus;
use App\Enum\ClientRequestType;
use App\Enum\SettingsType;
use App\Models\Appointment;
use App\Models\ClientRequest;
use App\Traits\WebSettingsHandler;
use Carbon\Carbon;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class AppointmentInfoModal extends SlideOver
{
    use Toast, WebSettingsHandler;

    public int|Appointment $appointment;

    public $remaining_text = '';

    public $cancel_message;

    public $can_cancel = false;

    public function mount(Appointment $appointment): void
    {
        try {
            $this->appointment = $appointment;
            $this->getSettings();

            $cancel_time = $this->getInt(SettingsType::client_page_appointment_cancel_time->name);

            $this->remaining_text = Carbon::now()->diffForHumans($this->appointment->date);

            $remaining_time = Carbon::now()->diffInMinutes($this->appointment->date, false);

            $this->can_cancel = AppointmentStatus::activeNoLate()->contains($this->appointment->status) && ! ($cancel_time == 0) && ! ($this->can_cancel > $remaining_time);
        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.');
            $this->close();
        }

    }

    public function save(): void
    {
        try {

            $validator = \Validator::make([
                'message' => $this->cancel_message,
            ], [
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            if ($this->appointment->hasCancelRequest()) {
                $this->error('İptal talebiniz bulunuyor.');
                $this->close();

                return;
            }

            $request = ClientRequest::create([
                'client_id' => auth()->user()->id,
                'message' => $this->cancel_message,
                'type' => ClientRequestType::appointment_cancel->name,
                'data' => [
                    'appointment_id' => $this->appointment->id,
                ],
            ]);

            if ($request) {
                $this->success('Talebiniz alınmıştır.');
                $this->close();

                return;
            }

            $this->error('Lütfen tekrar deneyin.');

        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.'.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.client.modal.appointment-info-modal');
    }
}
