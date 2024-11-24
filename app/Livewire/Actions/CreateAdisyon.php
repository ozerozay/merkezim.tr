<?php

namespace App\Livewire\Actions;

use App\Models\User;
use App\Traits\ServicePackageProductHandler;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateAdisyon extends SlideOver
{
    use ServicePackageProductHandler, Toast;

    public int|User $client;

    public $staff_ids = [];

    public $date;

    public $message;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->date = date('Y-m-d');
        $this->selected_services = collect();
        $this->selected_payments = collect();
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-adisyon');
    }
}
