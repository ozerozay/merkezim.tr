<?php

namespace App\Livewire\Modals;

use App\Actions\Spotlight\Actions\Get\GetKasas;
use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\Modal\Modal;

class SelectPaymentModal extends Modal
{
    use Toast;

    public int|User $client;

    public $kasa_id = null;

    public $gift = false;

    public $date;

    public $price = 0.0;

    public $kasa_collection;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->date = date('Y-m-d');
        $this->kasa_collection = GetKasas::run([$this->client->branch_id]);
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

    public function save(): void
    {
        $validator = \Validator::make([
            'kasa_id' => $this->kasa_id,
            'date' => $this->date,
            'price' => $this->price,
        ], [
            'kasa_id' => 'required',
            'date' => 'required',
            'price' => 'required|decimal:0,2|min:1',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->success('Peşinat eklendi.');
        $this->dispatch('modal-payment-added', $validator->validated());
        $this->kasa_id = null;
    }

    public function render()
    {
        return view('livewire.spotlight.modals.select-payment-modal');
    }
}
