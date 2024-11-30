<?php

namespace App\Livewire\Settings\Defination\Staff;

use App\Models\User;
use App\Traits\StrHelper;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;
use WireElements\Pro\Components\SlideOver\SlideOver;

class StaffEdit extends SlideOver
{
    use StrHelper, Toast;

    public int|User $staff;

    public $can_login = false;

    public $staff_branches = [];

    public $role;

    public $active;

    public function mount(User $staff)
    {
        $this->staff = $staff;
        $this->fill($staff);
        $this->role = $staff->roles()->first()->name;
    }

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function save()
    {
        $validator = \Validator::make([
            'staff_branches' => $this->staff_branches,
            'role' => $this->role,
            'can_login' => $this->can_login,
            'active' => $this->active,
        ], [
            'staff_branches' => 'required',
            'role' => 'required|exists:roles,name',
            'can_login' => 'required|boolean',
            'active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $user = $this->staff;

        $user->syncRoles([$this->role]);
        $user->staff_branches = $this->staff_branches;
        $user->active = $this->active;
        $user->can_login = $this->can_login;
        $user->save();

        $this->success('Personel düzenlendi.');
        $this->close(andDispatch: ['defination-staff-update']);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.staff.staff-edit', [
            'roles' => Role::all(),
        ]);
    }
}
