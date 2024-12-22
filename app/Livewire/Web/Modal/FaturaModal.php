<?php

namespace App\Livewire\Web\Modal;

use WireElements\Pro\Components\Modal\Modal;

class FaturaModal extends Modal
{
    public $fatura;

    public function selectStandart(): void
    {
        $this->dispatch('update-checkout-fatura', null);
        $this->close();
    }

    public function selectTC(): void
    {
        $this->dispatch('update-checkout-fatura', $this->fatura);
        $this->close();
    }

    public function render()
    {
        return view('livewire.client.modal.fatura-modal');
    }
}
