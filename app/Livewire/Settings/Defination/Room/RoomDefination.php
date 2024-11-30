<?php

namespace App\Livewire\Settings\Defination\Room;

use App\Models\ServiceRoom;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class RoomDefination extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'defination-room-update' => '$refresh',
    ];

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function rooms()
    {
        return ServiceRoom::query()
            ->whereIn('branch_id', auth()->user()->staff_branches)
            ->orderBy('branch_id')
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->with('branch:id,name', 'categories:id,name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.room.room-defination', [
            'rooms' => $this->rooms(),
        ]);
    }
}
