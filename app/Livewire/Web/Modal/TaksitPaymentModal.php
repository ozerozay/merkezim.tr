<?php

namespace App\Livewire\Web\Modal;

use App\Enum\PaymentType;
use App\Enum\SettingsType;
use App\Rules\PriceValidation;
use App\Traits\WebSettingsHandler;
use Illuminate\Support\Collection;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class TaksitPaymentModal extends SlideOver
{
    use Toast, WebSettingsHandler;

    public ?Collection $paymentTypes;

    public float $price_late = 0.0;

    public float $price_total = 0.0;

    public $price_manuel;

    public function mount(): void
    {
        try {

            $this->getSettings();

            $this->paymentTypes = $this->getCollection(SettingsType::client_payment_types->name);

            if ($this->paymentTypes->isEmpty()) {
                $this->error('Lütfen tekrar deneyin.');
                $this->close();

                return;
            }

            $this->price_late = auth()->user()->totalLateDebt();

            $this->price_total = auth()->user()->totalDebt();

            if ($this->price_total < 1) {
                $this->success('Ödemeniz bulunmuyor.');
                $this->close();

                return;
            }

        } catch (\Throwable $e) {

        }
    }

    public function payManuel(): void
    {

        $validator = \Validator::make([
            'price_manuel' => $this->price_manuel,
        ], [
            'price_manuel' => ['required', 'decimal:0,2', new PriceValidation],
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return;
        }

        $arguments = [
            'type' => PaymentType::taksit->name ?? null,
            'data' => $this->price_manuel ?? null,
        ];

        if ($this->price_manuel > $this->price_total) {
            $this->error('En fazla '.$this->price_total.' TL ödeme yapabilirsiniz.');

            return;
        }

        $this->dispatch('slide-over.open', [
            'component' => 'web.shop.checkout-page',
            'arguments' => $arguments,
        ]);

    }

    public function render()
    {
        return view('livewire.client.modal.taksit-payment-modal');
    }
}
