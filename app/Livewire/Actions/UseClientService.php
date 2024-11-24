<?php

namespace App\Livewire\Actions;

use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class UseClientService extends SlideOver
{
    use Toast;

    public int|User $client;

    public $service_ids = [];

    public $message = null;

    public $seans = 1;

    public function mount(User $client): void
    {
        $this->client = $client;
    }

    public function save(): void {}

    public function render()
    {
        return view('livewire.spotlight.actions.use-client-service');
    }
}
