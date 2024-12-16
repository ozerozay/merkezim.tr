<?php

namespace App\Livewire\Modals\Approve;

use App\Models\Approve;
use Livewire\Attributes\On;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ApproveModal extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    protected $listeners = [
        'refresh-approves' => '$refresh',
    ];

    #[On('approve-cancel')]
    public function cancelApprove($approveID, $message)
    {
        dump($approveID, $message);
    }

    #[On('approve-ok')]
    public function okApprove($approveID, $message)
    {
        dump($approveID, $message);
    }

    public function render()
    {
        return view('livewire.spotlight.modals.approve.approve-modal', [
            'approves' => Approve::where('status', 'waiting')->latest()->paginate(10),
        ]);
    }
}
