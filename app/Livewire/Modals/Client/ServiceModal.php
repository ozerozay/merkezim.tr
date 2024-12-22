<?php

namespace App\Livewire\Modals\Client;

use App\Actions\Spotlight\Actions\Update\DeleteClientServiceAction;
use App\Actions\Spotlight\Actions\Update\UpdateClientServiceAction;
use App\Actions\Spotlight\Actions\Update\UpdateClientServiceStatusAction;
use App\Models\ClientService;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ServiceModal extends SlideOver
{
    use Toast;

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

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

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

    public function delete(): void
    {
        $validator = \Validator::make([
            'id' => $this->service->id,
            'message' => $this->messageDelete,
        ], [
            'id' => 'required|exists:client_services',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        DeleteClientServiceAction::run($validator->validated());

        $this->dispatch('refresh-client-services');
        $this->success('Hizmet silindi.');
        $this->close();
    }

    public function edit(): void
    {
        $validator = \Validator::make([
            'id' => $this->service->id,
            'message' => $this->messageEdit,
            'remaining' => $this->service_remaining,
            'total' => $this->service_total,
            'sale_id' => $this->service_sale_id,
            'service_id' => $this->service_service_id,
        ], [
            'id' => 'required|exists:client_services',
            'message' => 'required',
            'remaining' => 'required|int',
            'total' => 'required|int',
            'sale_id' => 'nullable|exists:sale,id',
            'service_id' => 'required|exists:services,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        UpdateClientServiceAction::run($validator->validated());

        $this->dispatch('refresh-client-services');
        $this->success('Hizmet düzenlendi.');
        $this->close();
    }

    public function changeStatus(): void
    {
        $validator = \Validator::make([
            'id' => $this->service->id,
            'message' => $this->messageStatus,
            'status' => $this->service_status,
        ], [
            'id' => 'required|exists:client_services',
            'message' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        UpdateClientServiceStatusAction::run($validator->validated());

        $this->dispatch('refresh-client-services');
        $this->success('Hizmet durumu düzenlendi.');
        $this->close();

    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.service-modal', [
            'service' => $this->service,
        ]);
    }
}
