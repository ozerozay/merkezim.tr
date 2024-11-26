<?php

namespace App\Livewire\Modals;

use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\Modal\Modal;

class SelectCouponModal extends Modal
{
    use Toast;

    public int|User $client;

    public int $coupon_id;

    public function mount(User $client): void
    {
        $this->client = $client;
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
            'coupon_id' => $this->coupon_id,
        ], [
            'coupon_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->success('Kupon eklendi.');
        $this->dispatch('modal-coupon-added', $validator->validated());
        $this->reset('coupon_id');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.modals.select-coupon-modal');
    }
}
