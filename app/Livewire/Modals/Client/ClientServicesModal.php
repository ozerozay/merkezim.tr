<?php

namespace App\Livewire\Modals\Client;

use App\Actions\Spotlight\Actions\Client\Get\GetClientServicesAction;
use App\Models\User;
use App\Traits\ClientProfilModalHandler;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ClientServicesModal extends SlideOver
{
    use ClientProfilModalHandler, Toast, WithoutUrlPagination, WithPagination;

    public int|User $client;

    public $sortBy = ['column' => 'status', 'direction' => 'desc'];

    public function mount(User $client): void
    {
        $this->client = $client;
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.client-services-modal', [
            'services' => GetClientServicesAction::run($this->client->id, true, $this->sortBy),

        ]);
    }
}
