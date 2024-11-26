<?php

namespace App\Livewire\Actions;

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
    public function selectedCouponChanged($coupon): void
    {
        $this->coupon = $coupon;
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-adisyon');
    }
}
