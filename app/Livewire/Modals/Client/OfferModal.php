<?php

namespace App\Livewire\Modals\Client;

use App\Models\Offer;
use App\OfferStatus;
use App\Traits\ClientProfilModalHandler;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class OfferModal extends SlideOver
{
    use Toast, ClientProfilModalHandler;

    public int|Offer $offer;
    public $group = 'group1';
    public $kasa_id;
    public $expire_date;
    public $delete_reason;

    public function mount(Offer $offer): void
    {
        $this->offer = $offer;
        $this->expire_date = $offer->expire_date?->format('Y-m-d');
    }

    public function updateDate()
    {
        $this->validate([
            'expire_date' => 'required|date|after:today'
        ]);

        $this->offer->update([
            'expire_date' => $this->expire_date
        ]);

        $this->success('Teklif son geçerlilik tarihi güncellendi.');
    }

    public function delete()
    {
        $this->validate([
            'delete_reason' => 'required|min:10'
        ]);

        $this->offer->update([
            'status' => OfferStatus::cancel,
            'cancel_reason' => $this->delete_reason
        ]);

        $this->success('Teklif iptal edildi.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.offer-modal');
    }
}
