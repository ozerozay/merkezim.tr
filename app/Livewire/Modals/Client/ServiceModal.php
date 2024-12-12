<?php

namespace App\Livewire\Modals\Client;

use App\Models\ClientService;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ServiceModal extends SlideOver
{
    public int|ClientService $service;

    public ?string $messageDelete = null;

    public ?string $messageEdit = null;

    public ?string $messageStatus = null;

    // EDIT
    public $service_remaining;

    public $service_total;

    public $service_sale_id;

    public $service_service_id;

    public $branch_id;

    public $client_id;

    public $service_status;

    public $group = 'group0';

    public function mount(ClientService $service): void
    {
        $this->service = $service;

        $this->service_remaining = $this->service->remaining;
        $this->service_total = $this->service->total;
        $this->service_sale_id = $this->service->sale_id;
        $this->service_service_id = $this->service->service_id;
        $this->branch_id = $this->service->branch_id;
        $this->client_id = $this->service->client_id;
        $this->service_status = $this->service->status->name;
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.service-modal', [
            'service' => $this->service,
        ]);
    }
}
