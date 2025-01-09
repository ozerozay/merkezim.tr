<?php

namespace App\Livewire\Web\Modal;

use Livewire\Component;
use WireElements\Pro\Components\Modal\Modal;

class LanguageModal extends Modal
{
    public function render()
    {
        return view('livewire.web.modal.language-modal');
    }
}
