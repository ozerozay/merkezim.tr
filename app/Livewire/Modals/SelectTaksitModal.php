<?php

namespace App\Livewire\Modals;

use Carbon\Carbon;
use Mary\Traits\Toast;
use WireElements\Pro\Components\Modal\Modal;

class SelectTaksitModal extends Modal
{
    use Toast;

    public $first_date;

    public $price = 0.0;

    public $amount = 1;

    public function mount(): void
    {
        $this->first_date = Carbon::now();
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
            'first_date' => $this->first_date,
            'price' => $this->price,
            'amount' => $this->amount,
        ], [
            'first_date' => 'required|after:today',
            'price' => 'required|min:1|decimal:0,2',
            'amount' => 'required|min:1',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->success('Taksit eklendi.');
        $this->dispatch('modal-taksit-added', $validator->validated());
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.modals.select-taksit-modal');
    }
}
