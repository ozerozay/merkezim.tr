<?php

namespace App\Livewire\Settings\Defination\Service;

use App\Traits\StrHelper;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ServiceCreate extends SlideOver
{
    use \Mary\Traits\Toast, StrHelper;

    public function render()
    {
        return view('livewire.settings.defination.service.service-create');
    }
}
