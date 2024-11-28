<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\CreateAdisyonAction;
use App\Enum\PermissionType;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateAdisyon extends SlideOver
{
    use Toast;

    public int|User $client;

    public $staff_ids = [];

    public $date;

    public $message;

    public $price;

    public ?Collection $selected_services;

    public ?Collection $selected_payments;

    public $coupon;

    public $couponPrice = 0;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->date = date('Y-m-d');
        $this->selected_services = collect();
        $this->selected_payments = collect();
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

        return $totalPrice < 0 ? 0 : $totalPrice;
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

    public function save(): void
    {
        if ($this->selected_services->count() == 0) {
            $this->error('Hizmet veya ürün seçmelisiniz.');

            return;
        }

        if ($this->remainingPayment() > 0) {
            $this->error('Tüm tutarı yapılandırmanız gerekiyor.'.$this->remainingPayment().'₺');

            return;
        }

        $validator = \Validator::make([
            'client_id' => $this->client->id,
            'date' => $this->date,
            'message' => $this->message,
            'price' => $this->totalPriceTotal(),
            'staff_ids' => $this->staff_ids,
            'services' => $this->selected_services->toArray(),
            'payments' => $this->selected_payments->toArray(),
            'user_id' => auth()->user()->id,
            'coupon_id' => $this->coupon?->id ?? null,
            'permission' => PermissionType::action_adisyon_create->name,
            'coupon_price' => $this->couponPrice,
        ], [
            'client_id' => 'required|exists:users,id',
            'date' => 'required',
            'message' => 'required',
            'price' => 'required|decimal:0,2',
            'staff_ids' => 'nullable|array',
            'services' => 'required|array',
            'payments' => 'required|array',
            'user_id' => 'required|exists:users,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'permission' => 'required',
            'coupon_price' => 'nullable',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateAdisyonAction::run($validator->validated());

        $this->close();
        $this->success(title: 'Adisyon oluşturuldu.');
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-adisyon');
    }
}
