<?php

namespace App\Livewire\Modals;

use App\Actions\Spotlight\Actions\Get\GetServices;
use App\Models\Service;
use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\Modal\Modal;

class SelectTaksitServiceModal extends Modal
{
    use Toast;

    public int|User $client;

    public $service_ids = [];

    public int $quantity = 1;

    public int $taksit;

    public $service_collection;

    public function mount(User $client, int $taksit): void
    {
        $this->client = $client;
        $this->service_collection = collect();
        $this->taksit = $taksit;
    }

    public function search(string $value = ''): void
    {
        $selectedOption = Service::whereIn('id', $this->service_ids)->get();
        $this->service_collection = GetServices::run([$this->client->branch_id], $this->client->gender, $value)->merge($selectedOption);
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'service_ids' => $this->service_ids,
            'taksit' => $this->taksit,
            'quantity' => $this->quantity,
        ], [
            'service_ids' => 'required|array',
            'taksit' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->success('Hizmet eklendi.');
        $this->dispatch('modal-taksit-service-added', $validator->validated());
        $this->service_ids = [];
        $this->reset('quantity');
    }

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function render()
    {
        return view('livewire.spotlight.modals.select-taksit-service-modal');
    }
}
