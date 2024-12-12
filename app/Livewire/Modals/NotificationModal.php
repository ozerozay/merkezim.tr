<?php

namespace App\Livewire\Modals;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class NotificationModal extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public function render()
    {
        auth()->user()->notifications->markAsRead();

        return view('livewire.spotlight.modals.notification-modal', [
            'notifications' => auth()->user()->notifications()->latest()->paginate(10),
        ]);
    }
}
