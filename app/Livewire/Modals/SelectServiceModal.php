<?php

namespace App\Livewire\Modals;

use App\Actions\Spotlight\Actions\Get\GetServices;
use App\Models\Service;
use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\Modal\Modal;

class SelectServiceModal extends Modal
{
    use Toast;

    public int|User $client;

    public $service_ids = [];

    public $gift = false;

    public int $quantity = 1;

    public $service_collection;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->service_collection = collect();
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
            'gift' => $this->gift,
            'quantity' => $this->quantity,
        ], [
            'service_ids' => 'required|array',
            'gift' => 'required|boolean',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }
        $this->success('Hizmet eklendi.');
        $this->dispatch('modal-service-added', $validator->validated());
        $this->service_ids = [];
        $this->reset('quantity', 'gift');
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
        return view('livewire.spotlight.modals.select-service-modal');
    }
}
