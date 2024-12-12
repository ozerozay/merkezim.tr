<?php

namespace App\Livewire\Web\Shop;

use WireElements\Pro\Components\SlideOver\SlideOver;

class CheckoutPage extends SlideOver
{
    public $paymentMethod = 'havale'; // Varsayılan ödeme yöntemi

    public $cardName;

    public $cardNumber;

    public $expiryDate;

    public $cvv;

    public $orderType;

    public $group = 'group1';

    public function processPayment()
    {
        if ($this->paymentMethod === 'kredi_karti') {
            // Kredi kartı ödeme işlemi
            $this->validate([
                'cardNumber' => 'required|digits:16',
                'expiryDate' => 'required',
                'cvv' => 'required|digits:3',
            ]);

            // Ödeme işleme mantığı
        } else {
            // Havale işlemi (gerekirse doğrulama eklenebilir)
        }

        // Ödeme sonrası işlem
    }

    public function render()
    {
        return view('livewire.client.shop.checkout-page');
    }
}
