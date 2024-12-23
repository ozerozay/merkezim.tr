<?php

namespace App\Livewire\Web\Shop;

use App\Actions\Spotlight\Actions\Web\Payment\CreateHavaleAction;
use App\Actions\Spotlight\Actions\Web\Payment\CreatePaymentTokenAction;
use App\Actions\Spotlight\Actions\Web\Payment\GetBinAction;
use App\Actions\Spotlight\Actions\Web\Payment\GetTaksitOranAction;
use App\Enum\PaymentType;
use App\Enum\SettingsType;
use App\Models\KasaHavale;
use App\Models\Payment;
use App\Traits\WebSettingsHandler;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CheckoutPage extends SlideOver
{
    use Toast, WebSettingsHandler;

    public $paymentMethod = 'kk'; // Varsayılan ödeme yöntemi

    public $cardName;

    public $cardNumber;

    public $expiryDate;

    public $cvv;

    public $orderType;

    public $group = 'group1';

    public $selectedMethod = 1;

    public $type;

    public $data;

    public $includeKDV = 0;

    public $includeKomisyon = true;

    public ?Collection $paymentTypes;

    public $havale_accounts;

    public ?Collection $taksit_orans;

    public $selectedInstallment = 'pesin';

    public $methods = [
        ['id' => 1, 'name' => 'ONLİNE KREDİ KARTI'],
        ['id' => 2, 'name' => 'HAVALE'],
    ];

    public $havale_group = 'group0';

    public $fatura = null;

    public ?Collection $bin;

    public $frame_code = null;

    public $frame_error = null;

    #[On('cardNumberValidated')]
    public function bin(): void
    {
        try {
            $this->bin = GetBinAction::run(\Str::replace('-', '', $this->cardNumber), auth()->user()->branch_id);

            if ($this->bin->isNotEmpty() && $this->bin->count() > 1) {
                $this->taksit_orans = GetTaksitOranAction::run($this->bin['brand'], auth()->user()->branch_id);
            } else {
                $this->bin = collect();
                $this->taksit_orans = collect();
            }

            $this->selectedInstallment = 'pesin';
        } catch (\Throwable $e) {

            $this->selectedInstallment = 'pesin';
        }
    }

    public function creditCardCheckout(): void
    {
        try {
            [$expiryMonth, $expiryYear] = $this->expiryDate != null ? explode('/', $this->expiryDate) : [null, null];
            $validator = \Validator::make([
                'cardNumber' => \Str::replace('-', '', $this->cardNumber),
                'cardName' => $this->cardName,
                'expiryMonth' => $expiryMonth,
                'expiryYear' => $expiryYear,
                'cvv' => $this->cvv,
                'selectedInstallment' => $this->selectedInstallment,
                'total' => number_format((float) $this->calculateTotalAndKomisyon(), 2),
                'fatura' => $this->fatura,
                'ara' => $this->calculateAraToplam(),
                'vergi' => $this->calculateVergi(),
                'discount' => $this->calculateDiscount(),
                'komisyon' => $this->calculateKomisyon(),
                'bin' => $this->bin->toArray(),
                'client_id' => auth()->user()->id,
                'type' => $this->type,
            ], [
                'cardNumber' => ['required', 'digits:16', function ($attribute, $value, $fail) {
                    if (! $this->luhnCheck($value)) {
                        return $fail('Kart numarası hatalı.');
                    }
                }],
                'cardName' => 'required',
                'expiryMonth' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])$/'],
                'expiryYear' => ['required', 'integer', 'digits:2', function ($attribute, $value, $fail) {
                    $expiryMonth = (int) request('expiryMonth'); // Ayı integer'a dönüştür
                    $currentYear = now()->year % 100; // Yalnızca iki haneli yıl
                    $currentMonth = now()->month;

                    if ($value < $currentYear || ($value == $currentYear && $expiryMonth < $currentMonth)) {
                        $fail('Kartın son kullanma tarihi geçmiş.');
                    }
                }],
                'cvv' => 'required|digits:3|numeric',
                'selectedInstallment' => 'nullable',
                'total' => 'required|decimal:0,2|min:1',
                'ara' => 'required',
                'vergi' => 'required',
                'discount' => 'required',
                'komisyon' => 'required',
                'fatura' => 'nullable',
                'bin' => 'array',
                'client_id' => 'required',
                'type' => 'required',
            ]);

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            $this->frame_code = CreatePaymentTokenAction::run($validator->validated());

        } catch (\Throwable $e) {
            $this->error('Lütfen kontrol ederek, tekrar deneyin.');
            $this->frame_code = null;
        }
    }

    public function luhnCheck($value)
    {
        $number = preg_replace('/\D/', '', $value); // non-numeric chars removal
        $length = strlen($number);
        $sum = 0;
        $shouldDouble = false;
        for ($i = $length - 1; $i >= 0; $i--) {
            $digit = $number[$i];
            if ($shouldDouble) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
            $shouldDouble = ! $shouldDouble;
        }

        return $sum % 10 === 0;
    }

    #[On('checkout-result-changed')]
    public function checkoutResultChanged($id, $status, $message): void
    {
        if ($status == 'error') {
            $this->frame_code = null;
            $this->frame_error = $message;

            Payment::where('unique_id', $id)->update([
                'status' => 'cancel',
            ]);
        } else {
            $this->close(withForce: true);
            $this->dispatch('slide-over.open', component: 'web.shop.payment-success-modal', arguments: ['id' => $id]);
        }
    }

    #[On('update-checkout-fatura')]
    public function changeFatura($fatura): void
    {
        $this->fatura = $fatura;
    }

    public function changeMethod($id): void
    {
        $this->selectedInstallment = 'pesin';
        $this->taksit_orans = collect();
        $this->selectedMethod = $id;
    }

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

    public function mount($type, $data): void
    {

        $this->type = $type;
        $this->data = $data;

        $this->paymentTypes = collect();
        $this->taksit_orans = collect();
        $this->bin = collect();

        $this->getSettings();

        if ($type == PaymentType::taksit->name) {
            $this->includeKDV = $this->getInt(SettingsType::payment_taksit_include_kdv->name);
            $this->includeKomisyon = $this->getBool(SettingsType::payment_taksit_include_komisyon->name);
            $this->paymentTypes = $this->getCollection(SettingsType::client_payment_types->name);
        }

        $this->havale_accounts = KasaHavale::query()
            ->where('active', true)
            ->whereHas('kasa', function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function calculateAraToplam(): float|int
    {
        if ($this->type == PaymentType::taksit->name) {
            return (float) $this->data;
        }

        return 0;
    }

    #[Computed]
    public function calculateVergi(): float|int
    {
        return $this->includeKDV > 0 ? ($this->calculateAraToplam() * $this->includeKDV / 100) : 0;
    }

    #[Computed]
    public function calculateDiscount(): float|int
    {
        if ($this->type == PaymentType::taksit->name) {
            return 0;
        }

        return 0;
    }

    #[Computed]
    public function calculateKomisyon(): float|int
    {
        try {
            $oran = 0;
            if ($this->selectedInstallment != 'pesin') {
                $oran = $this->taksit_orans['taksit_'.$this->selectedInstallment];
            }

            //dump($oran);

            return $this->calculateTotal() * (float) $oran / 100;
        } catch (\Throwable $e) {
            dump($e);

            return 0;
        }

    }

    #[Computed]
    public function calculateTotal(): float|int
    {
        return $this->calculateAraToplam() + $this->calculateVergi() - ($this->calculateDiscount() * -1);
    }

    #[Computed]
    public function calculateTotalAndKomisyon(): float|int
    {
        return $this->calculateTotal() + $this->calculateKomisyon();
    }

    public function submitHavale(): void
    {
        $info = [
            'ara' => $this->calculateAraToplam(),
            'vergi' => $this->calculateVergi(),
            'discount' => $this->calculateDiscount(),
            'total' => $this->calculateTotal(),
            'totalKomisyon' => $this->calculateTotalAndKomisyon(),
            'komisyon' => $this->calculateKomisyon(),
            'fatura' => $this->fatura,
            'type' => $this->type,
            'group' => $this->group,
        ];

        if ($payment = CreateHavaleAction::run($info)) {

            $this->dispatch('slide-over.open', component: 'web.shop.payment-success-modal', arguments: [
                'id' => $payment->unique_id,
            ]);
        } else {
            $this->error('Lütfen tekrar deneyin.');
        }
    }

    public function render()
    {
        return view('livewire.client.shop.checkout-page');
    }
}
