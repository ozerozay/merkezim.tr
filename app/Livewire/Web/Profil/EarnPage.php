<?php

namespace App\Livewire\Web\Profil;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.client')]
#[Title('Kullan - Kazan')]
class EarnPage extends Component
{
    public function render()
    {
        return view('livewire.client.profil.earn-page');
    }
}
