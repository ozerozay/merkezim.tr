<?php

namespace App\Livewire\Modals\Client;

use App\Actions\Spotlight\Actions\Update\DeleteClientTaksitAction;
use App\Actions\Spotlight\Actions\Update\UpdateClientTaksitDateAction;
use App\Actions\Spotlight\Actions\Update\UpdateClientTaksitStatusAction;
use App\Models\ClientTaksit;
use App\Models\ClientTaksitsLock;
use Livewire\Attributes\On;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class TaksitModal extends SlideOver
{
    use Toast;

    public int|ClientTaksit $taksit;

    public ?string $date;

    public ?string $message;

    public ?string $messageDelete;

    public $taksitStatus;

    public $messageStatus;

    public $group = 'group0';

    public function mount(ClientTaksit $taksit): void
    {
        $this->taksit = $taksit->load('clientTaksitsLocks.service:id,name');

        $this->date = $this->taksit->date;
        $this->taksitStatus = $this->taksit->status->name;
    }

    #[On('modal-taksit-service-added')]
    public function addLockedService($info): void
    {
        try {
            foreach ($info['service_ids'] as $service) {
                $this->taksit->clientTaksitsLocks()->create([
                    'client_id' => $this->taksit->client_id,
                    'service_id' => $service,
                    'quantity' => $info['quantity'],
                ]);
            }
        } catch (\Throwable $e) {

        }
    }

    public function deleteLocked($id): void
    {
        ClientTaksitsLock::where('id', $id)->delete();
    }

    public function changeStatus(): void
    {
        $validator = \Validator::make([
            'id' => $this->taksit->id,
            'message' => $this->messageStatus,
            'status' => $this->service_status,
        ], [
            'id' => 'required|exists:client_taksits',
            'message' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        UpdateClientTaksitStatusAction::run($validator->validated());

        $this->dispatch('refresh-client-taksits');
        $this->success('Taksit tarihi düzenlendi.');
        $this->close();
    }

    public function edit(): void
    {
        $validator = \Validator::make([
            'date' => $this->date,
            'message' => $this->message,
            'id' => $this->taksit->id,
        ], [
            'date' => 'required|date',
            'message' => 'required',
            'id' => 'required|exists:client_taksits',
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        UpdateClientTaksitDateAction::run($validator->validated());

        $this->dispatch('refresh-client-taksits');
        $this->success('Taksit tarihi düzenlendi.');
        $this->close();
    }

    public function delete(): void
    {
        $validator = \Validator::make([
            'id' => $this->taksit->id,
            'message' => $this->messageDelete,
        ], [
            'id' => 'required|exists:client_taksits',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        DeleteClientTaksitAction::run($validator->validated());

        $this->dispatch('refresh-client-taksits');
        $this->success('Taksit silindi.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.taksit-modal');
    }
}
