<?php

namespace App\Livewire\Web\Modal;

use WireElements\Pro\Components\Modal\Modal;

class EditProfileModel extends Modal
{
    public function render()
    {
        return view('livewire.client.modal.edit-profile-model');
    }
}
