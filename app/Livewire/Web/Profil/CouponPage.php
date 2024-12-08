<?php

namespace App\Livewire\Web\Profil;

use App\Actions\Spotlight\Actions\Web\GetPageCouponAction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('components.layouts.client')]
#[Title('Kuponlarım')]
class CouponPage extends Component
{
    use Toast;

    public function render()
    {
        return view('livewire.client.profil.coupon-page', [
            'data' => GetPageCouponAction::run(),
        ]);
    }
}
