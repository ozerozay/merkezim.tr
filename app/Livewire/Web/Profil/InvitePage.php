<?php

namespace App\Livewire\Web\Profil;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.client')]
#[Title('Davet Et - Kazan')]
class InvitePage extends Component
{
    public function render()
    {
        return view('livewire.client.profil.invite-page');
    }
}
