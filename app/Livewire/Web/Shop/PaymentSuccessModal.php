<?php

namespace App\Livewire\Web\Shop;

use App\Models\Payment;
use WireElements\Pro\Components\SlideOver\SlideOver;

class PaymentSuccessModal extends SlideOver
{
    public ?Payment $payment = null;

    public function mount($id): void
    {
        $this->payment = Payment::where('unique_id', $id)->first();

        if (! $this->payment) {
            $this->close();
        }
    }

    public function render()
    {
        return view('livewire.client.shop.payment-success-modal');
    }
}
