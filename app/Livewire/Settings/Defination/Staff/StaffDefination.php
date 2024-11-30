<?php

namespace App\Livewire\Settings\Defination\Staff;

use App\Models\User;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class StaffDefination extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'defination-staff-update' => '$refresh',
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

    public function staffs()
    {
        return User::query()
            ->role(['admin', 'staff'])
            ->orderBy('can_login', 'desc')
            ->orderBy('name')
            ->with('roles')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.staff.staff-defination', [
            'staffs' => $this->staffs(),
        ]);
    }
}
