<?php

namespace App\Livewire\Web\Shop;

use App\Actions\Spotlight\Actions\Web\Payment\CreateHavaleAction;
use App\Actions\Spotlight\Actions\Web\Payment\CreatePaymentTokenAction;
use App\Actions\Spotlight\Actions\Web\Payment\GetBinAction;
use App\Actions\Spotlight\Actions\Web\Payment\GetTaksitOranAction;
use App\Enum\PaymentStatus;
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

    public $includeKomisyon = 0.0;

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

    public static function attributes(): array
    {
        return [
            // Set the slide-over size to 2xl, you can choose between:
            // xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, fullscreen
            //'size' => 'fullscreen',
        ];
    }

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
                'client_id' => auth()->user()->id,
                'cardNumber' => \Str::replace('-', '', $this->cardNumber),
                'cardName' => $this->cardName,
                'expiryMonth' => $expiryMonth,
                'expiryYear' => $expiryYear,
                'cvv' => $this->cvv,
                'selectedInstallment' => $this->selectedInstallment,
                'total' => $this->numberFormat($this->calculateTotalAndKomisyon()),
                'fatura' => $this->fatura,
                'ara' => $this->numberFormat($this->calculateAraToplam()),
                'vergi' => $this->numberFormat($this->calculateVergi()),
                'discount' => $this->numberFormat($this->calculateDiscount()),
                'komisyon' => $this->numberFormat($this->calculateKomisyon()),
                'bin' => $this->bin->toArray(),
                'client_id' => auth()->user()->id,
                'type' => $this->paymentMethod,
                'data' => $this->data,
            ], [
                'client_id' => 'required',
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
                'type' => 'required',
                'data' => 'required',
                'client_id' => 'required',
            ]);

            dump($validator->validated());

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            $this->frame_code = CreatePaymentTokenAction::run($validator->validated());

        } catch (\Throwable $e) {
            dump($e);
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

            $payment = Payment::where('unique_id', $id)->first();

            if ($payment) {
                $payment->update([
                    'status' => PaymentStatus::error->name,
                    'status_message' => $message,
                ]);
            }

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
        $this->paymentMethod = $id == 1 ? 'kk' : 'havale';
        $this->selectedMethod = $id;
    }

    public function mount($type, $data): void
    {

        $this->type = $type;
        $this->data = $data;

        $this->paymentTypes = collect();
        $this->taksit_orans = collect();
        $this->bin = collect();

        $this->getSettings();

        $this->paymentTypes = $this->getCollection(SettingsType::client_payment_types->name);

        if ($this->paymentTypes->isEmpty()) {
            $this->close();

            return;
        }
        if ($this->paymentTypes->count() == 1) {
            $this->paymentMethod = $this->paymentTypes->first();
            $this->selectedMethod = $this->paymentMethod == 'kk' ? 1 : 2;
        }

        $type_settings = match ($type) {
            PaymentType::taksit->name => [
                'includeKDV' => SettingsType::payment_taksit_include_kdv->name,
                'includeKomisyon' => SettingsType::payment_taksit_include_komisyon->name,
            ],
            PaymentType::tip->name => [
                'includeKDV' => SettingsType::payment_tip_include_kdv->name,
                'includeKomisyon' => SettingsType::payment_tip_include_komisyon->name,
            ],
            PaymentType::offer->name => [
                'includeKDV' => SettingsType::payment_offer_include_kdv->name,
                'includeKomisyon' => SettingsType::payment_offer_include_komisyon->name,
            ],
            default => null,
        };

        if ($type_settings) {
            $this->includeKDV = $this->getInt($type_settings['includeKDV']);
            $this->includeKomisyon = $this->getFloat($type_settings['includeKomisyon']);
            //dump($this->includeKomisyon);
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
            return $this->numberFormat((float) $this->data);
        } elseif ($this->type == PaymentType::tip->name) {
            return $this->numberFormat((float) $this->data['amount']);
        } elseif ($this->type == PaymentType::offer->name) {
            return $this->numberFormat((float) $this->data['amount']);
        }

        return 0;
    }

    #[Computed]
    public function calculateVergi(): float|int
    {
        return $this->numberFormat($this->includeKDV > 0 ? ($this->calculateAraToplam() * $this->includeKDV / 100) : 0);
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
            $oran = 0.0;
            if ($this->selectedInstallment != 'pesin') {
                $oran = $this->taksit_orans['taksit_'.$this->selectedInstallment];
            } else {
                $oran = $this->includeKomisyon;
            }
            //dump($oran);

            return $this->numberFormat($this->calculateTotal() * (float) $oran / 100);
        } catch (\Throwable $e) {
            dump($e);

            return 0;
        }

    }

    #[Computed]
    public function calculateTotal(): float|int
    {
        return $this->numberFormat($this->calculateAraToplam() + $this->calculateVergi() - ($this->calculateDiscount() * -1));
    }

    #[Computed]
    public function calculateTotalAndKomisyon(): float|int
    {
        return $this->numberFormat($this->calculateTotal() + $this->calculateKomisyon());
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
            'client_id' => auth()->user()->id,
            'data' => $this->data,
        ];

        if ($payment = CreateHavaleAction::run($info)) {

            $this->dispatch('slide-over.open', component: 'web.shop.payment-success-modal', arguments: [
                'id' => $payment->unique_id,
            ]);
        } else {
            $this->error('Lütfen tekrar deneyin.');
        }
    }

    public function numberFormat($number): string
    {
        return number_format((float) $number, 2, '.', '');
    }

    public function render()
    {
        return view('livewire.client.shop.checkout-page');
    }
}
