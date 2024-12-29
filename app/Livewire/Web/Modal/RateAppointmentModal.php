<?php

namespace App\Livewire\Web\Modal;

use App\Enum\PaymentType;
use App\Models\Appointment;
use App\Rules\PriceValidation;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class RateAppointmentModal extends SlideOver
{
    use Toast;

    public int|Appointment $appointment;

    public $rank = 0;

    public $customAmount = 0;

    public $message;

    public function mount(Appointment $appointment): void
    {
        $this->appointment = $appointment;
        if ($this->appointment->review) {
            //$this->close();
        }
    }

    public function save(): void
    {

        try {
            $validator = \Validator::make([
                'rank' => $this->rank,
                'message' => $this->message,
                'appointment_id' => $this->appointment->id,
                'tip' => $this->customAmount,
            ], [
                'appointment_id' => 'required',
                'rank' => 'required|integer|min:1|max:5',
                'message' => 'nullable',
                'tip' => ['nullable', new PriceValidation],
            ]);

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            $review = $this->appointment->review()->create($validator->validated());

            $arguments = [
                'type' => PaymentType::tip->name ?? null,
                'data' => [
                    'review_id' => $review->id,
                    'amount' => $this->customAmount,
                ],
            ];

            $this->dispatch('refresh-client-appointments');

            if ($this->customAmount > 5) {
                $this->dispatch('slide-over.open', component: 'web.shop.checkout-page', arguments: $arguments);
            } else {
                $this->success('Teşekkür ederiz.');
                $this->close();
            }

        } catch (\Throwable $e) {
            dump($e);
        }

    }

    public function render()
    {
        return view('livewire.client.modal.rate-appointment-modal');
    }
}
