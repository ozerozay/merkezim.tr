<?php

namespace App\Livewire\Modals\Client;

use App\Models\Coupon;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CouponModal extends SlideOver
{
    public int|Coupon $coupon;

    public $group = 'group';

    public function mount(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.coupon-modal');
    }
}
