<?php

namespace App\Livewire\Actions;

use App\Models\User;
use App\Traits\ServicePackageProductHandler;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateClientProductSale extends SlideOver
{
    use ServicePackageProductHandler, Toast;

    public int|User $client;

    public $date;

    public $staff_ids = [];

    public $message = null;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->date = date('Y-m-d');
        $this->selected_payments = collect();
        $this->selected_services = collect();
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-client-product-sale');
    }
}
