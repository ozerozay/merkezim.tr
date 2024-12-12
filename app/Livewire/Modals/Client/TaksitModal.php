<?php

namespace App\Livewire\Modals\Client;

use App\Models\ClientTaksit;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class TaksitModal extends SlideOver
{
    use Toast;

    public int|ClientTaksit $taksit;

    public ?string $date;

    public ?string $message;

    public ?string $messageDelete;

    public $group = 'group1';

    public function mount(ClientTaksit $taksit)
    {
        $this->taksit = $taksit;

        $this->date = $this->taksit->date;
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.taksit-modal');
    }
}
