<?php

namespace App\Livewire\Modals\Client;

use App\Models\Offer;
use WireElements\Pro\Components\SlideOver\SlideOver;

class OfferModal extends SlideOver
{
    public int|Offer $offer;

    public $group = 'group1';

    public function mount(Offer $offer): void
    {
        $this->offer = $offer;
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.offer-modal');
    }
}
