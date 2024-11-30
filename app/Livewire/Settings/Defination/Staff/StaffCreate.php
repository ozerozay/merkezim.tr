<?php

namespace App\Livewire\Settings\Defination\Staff;

use App\Models\User;
use App\Traits\StrHelper;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;
use WireElements\Pro\Components\SlideOver\SlideOver;

class StaffCreate extends SlideOver
{
    use StrHelper, Toast;

    public $client_id;

    public $branch_ids = [];

    public $role;

    public $roles = [];

    public function mount(): void
    {
        $this->roles = Role::all();
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

    public function save(): void
    {
        $validator = \Validator::make([
            'branch_ids' => $this->branch_ids,
            'role' => $this->role,
            'client_id' => $this->client_id,
        ], [
            'branch_ids' => 'required',
            'role' => 'required|exists:roles,name',
            'client_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $user = User::where('id', $this->client_id)->first();

        $user->roles()->delete();
        $user->assignRole($this->role);
        $user->staff_branches = $this->branch_ids;
        $user->save();

        $this->success('Personel oluşturuldu, yetkilendirmek için düzenleyin.');
        $this->close(andDispatch: ['defination-staff-update']);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.staff.staff-create', [
            'roles' => $this->roles,
        ]);
    }
}
