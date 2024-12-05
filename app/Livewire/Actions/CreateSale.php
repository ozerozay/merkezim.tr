<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\CreateSaleAction;
use App\Enum\PermissionType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateSale extends SlideOver
{
    use Toast;

    public int|User $client;

    public int $step = 1;

    public $sale_type_id;

    public $date;

    public $staff_ids = [];

    public $price = 0;

    public $expire_date = 0;

    public $message;

    public $couponPrice = 0;

    public ?Collection $selected_services;

    public ?Collection $selected_payments;

    public ?Collection $selected_taksits;

    public $coupon;

    public $firstDate;

    public $taksitSayisi = 1;

    public $nextLabel = 'Hizmet Bölümüne Geç';

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->date = date('Y-m-d');
        $this->firstDate = date('Y-m-d');
        $this->selected_services = collect();
        $this->selected_payments = collect();
        $this->selected_taksits = collect();
    }

    public function next()
    {
        if ($this->step == 1) {
            $validator = \Validator::make([
                'sale_type_id' => $this->sale_type_id,
                'date' => $this->date,
                'message' => $this->message,
            ], [
                'sale_type_id' => 'required',
                'date' => 'required',
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            $this->step++;
            $this->nextLabel = 'İleri';
        } elseif ($this->step == 2) {
            if ($this->selected_services->isEmpty()) {
                $this->error('Hizmet veya paket seçmelisiniz.');

                return;
            }

            if ($this->remainingPayment() > 0) {
                $this->step++;
            } else {
                $this->create();
            }
        } elseif ($this->step == 3) {
            if ($this->selected_taksits->isEmpty()) {
                $this->error('Yapılandırmanız gerekiyor.');

                return;
            }

            $this->create();

        }

    }

    public function create(): void
    {
        if ($this->selected_taksits->isEmpty()) {
            if ($this->remainingPayment() > 0) {
                $this->error('Tüm tutarı yapılandırmanız gerekiyor.');

                return;
            }
        }

        $validator = \Validator::make([
            'client_id' => $this->client->id,
            'sale_type_id' => $this->sale_type_id,
            'date' => $this->date,
            'staff_ids' => $this->staff_ids,
            'sale_price' => $this->totalPrice(),
            'services' => $this->selected_services->toArray(),
            'nakits' => $this->selected_payments->toArray(),
            'taksits' => $this->selected_taksits->toArray(),
            'price_real' => $this->totalPriceTotal(),
            'message' => $this->message,
            'user_id' => auth()->user()->id,
            'expire_date' => $this->expire_date,
            'coupon_id' => $this->coupon?->id ?? null,
            'permission' => PermissionType::action_client_sale->name,
        ], [
            'client_id' => 'required|exists:users,id',
            'sale_type_id' => 'required|exists:sale_types,id',
            'date' => 'required',
            'staff_ids' => 'nullable|array',
            'sale_price' => 'nullable|decimal:0,2',
            'services' => 'required',
            'nakits' => 'nullable',
            'taksits' => 'nullable',
            'price_real' => 'decimal:0,2',
            'message' => 'required',
            'user_id' => 'required',
            'expire_date' => 'required',
            'permission' => 'required',
            'coupon_id' => 'nullable',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateSaleAction::run($validator->validated());

        $this->success('Satış oluşturuldu.');
        $this->close();
    }

    public function back()
    {
        if ($this->step == 3) {
            $this->selected_taksits = collect();
        }
        $this->step--;
    }

    public function taksitlendir()
    {
        $kalan = $this->remainingPayment();

        $taksit_sayisi = $this->taksitSayisi;

        $taksit_para = $kalan / $taksit_sayisi;

        $taksit_tarih = $this->firstDate;

        $taksit_para_ondalik = number_format($taksit_para, 2, '.', '');

        for ($i = 0; $i < $taksit_sayisi; $i++) {
            $this->selected_taksits->push([
                'id' => $i,
                'price' => $taksit_para_ondalik,
                'date' => Carbon::parse($taksit_tarih)->addMonths($i)->format('d/m/Y'),
            ]);
        }

        $cikan_taksit = 0.0;

        foreach ($this->selected_taksits as $t) {
            $cikan_taksit += $t['price'];
        }

        $eklenecek = $kalan - $cikan_taksit;

        $lastItemKey = $this->selected_taksits->keys()->last();

        $this->selected_taksits = $this->selected_taksits->map(function ($item, $key) use ($lastItemKey, $eklenecek) {
            if ($key === $lastItemKey) {
                return [
                    'id' => $item['id'],
                    'price' => $item['price'] + $eklenecek,
                    'date' => $item['date'],
                ];  // Yeni değer
            }

            return $item;
        });

    }

    public function deleteTaksits()
    {
        $this->selected_taksits = collect();
    }

    public function updatedPrice($value)
    {
        $this->price = $value;
        $this->dispatch('price-changed', $value)->to('spotlight.components.add-product-coupon-service-package');
    }

    #[On('selected-payments-changed')]
    public function selectedPaymentsChanged($selected_payments): void
    {
        //dump($selected_payments);
        $this->selected_payments = collect($selected_payments);
    }

    #[On('selected-services-changed')]
    public function selectedServicesChanged($selected_services): void
    {
        //dump($selected_services);
        $this->selected_services = collect($selected_services);
    }

    #[On('selected-coupon-changed')]
    public function selectedCouponChanged($coupon, $couponPrice): void
    {
        $this->coupon = $coupon;
        $this->couponPrice = $couponPrice;
    }

    #[\Livewire\Attributes\Computed]
    public function totalPrice(): mixed
    {
        if ($this->price > 0) {
            return $this->price - $this->couponPrice;
        }

        $totalPrice = $this->selected_services->where('gift', false)->sum('price') - $this->couponPrice;

        $totalPrice = $totalPrice < 0 ? 0 : $totalPrice;

        return $totalPrice;
    }

    #[\Livewire\Attributes\Computed]
    public function totalPriceTotal(): mixed
    {
        if ($this->price > 0) {
            return $this->price;
        }

        return $this->selected_services->where('gift', false)->sum('price');
    }

    #[\Livewire\Attributes\Computed]
    public function totalPayment(): mixed
    {
        return $this->selected_payments->sum('price');
    }

    #[\Livewire\Attributes\Computed]
    public function remainingPayment(): float|int
    {
        return $this->totalPrice() - $this->totalPayment();
    }

    #[\Livewire\Attributes\Computed]
    public function totalGift(): mixed
    {
        return $this->selected_services->where('gift', true)->sum('price');
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-sale');
    }
}
