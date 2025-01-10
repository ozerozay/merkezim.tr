<?php

namespace App\Livewire\MerkezimSpotlight\Forms;

use App\Actions\Spotlight\Actions\Kasa\CreatePaymentAction;
use App\Enum\PermissionType;
use App\Rules\PriceValidation;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class PaymentForm extends Component
{
    public $payment_date;
    public $kasa_id;
    public $masraf_id;
    public $price;
    public $message;

    public function mount()
    {
        $this->payment_date = date('Y-m-d');
    }

    public function masrafSave()
    {
        try {

            // Kaydetme işlemi
            $validator = \Validator::make([
                'date' => $this->payment_date,
                'kasa_id' => $this->kasa_id,
                'message' => $this->message,
                'masraf_id' => $this->masraf_id,
                'price' => $this->price * -1,
                'type' => 'masraf',
                'user_id' => auth()->user()->id,
                'permission' => PermissionType::kasa_make_payment,
                'paid' => false,
            ], [
                'date' => 'required',
                'kasa_id' => 'required|exists:kasas,id',
                'message' => 'required',
                'masraf_id' => 'required|exists:masraf,id',
                'price' => ['required', new PriceValidation, 'min:1'],
                'type' => 'required',
                'user_id' => 'required',
                'permission' => 'required',
                'paid' => 'required',
            ]);

            if ($validator->fails()) {
                $this->dispatch('spotlight-notification', [
                    'message' => $validator->errors()->first(),
                    'type' => 'error'
                ]);
                return;
            }

            CreatePaymentAction::run($validator->validated());

            // MerkezimSpotlight'a başarı bildirimi gönder
            $this->dispatch('spotlight-notification', [
                'message' => 'Ödeme başarıyla kaydedildi',
                'type' => 'success'
            ]);

            // Form temizleme ve kapatma
            $this->dispatch('payment-saved');
            $this->dispatch('close-payment-form');
        } catch (\Exception $e) {
            // MerkezimSpotlight'a hata bildirimi gönder
            $this->dispatch('spotlight-notification', [
                'message' => 'Ödeme kaydedilirken bir hata oluştu: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="p-4">
            <div class="animate-pulse space-y-4">
                <div class="h-8 bg-base-200 rounded w-1/4"></div>
                <div class="space-y-3">
                    <div class="h-4 bg-base-200 rounded"></div>
                    <div class="h-4 bg-base-200 rounded w-5/6"></div>
                </div>
                <div class="h-10 bg-base-200 rounded"></div>
                <div class="h-10 bg-base-200 rounded"></div>
                <div class="h-10 bg-base-200 rounded"></div>
                <div class="h-20 bg-base-200 rounded"></div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.merkezim-spotlight.forms.payment-form');
    }
}
