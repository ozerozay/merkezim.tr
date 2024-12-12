<?php

namespace App\Livewire\Modals\Client;

use App\Models\Adisyon;
use WireElements\Pro\Components\SlideOver\SlideOver;

class AdisyonModal extends SlideOver
{
    public int|Adisyon $adisyon;

    public $group = 'group1';

    public $messageDelete;

    public function mount(int|Adisyon $adisyon)
    {
        $this->adisyon = $adisyon;
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.adisyon-modal');
    }
}
