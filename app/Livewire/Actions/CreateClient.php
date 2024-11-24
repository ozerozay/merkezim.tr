<?php

namespace App\Livewire\Actions;

use App\Actions\Client\CreateClientAction;
use App\Enum\PermissionType;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateClient extends SlideOver
{
    use Toast;

    public $name = '';

    public $branch_id = null;

    public $country = '90';

    public $phone;

    public $tckimlik;

    public $birth_date;

    public $il;

    public $ilce;

    public $gender = 1;

    public $adres;

    public $email;

    public function mount(): void
    {
        $this->branch_id = auth()->user()->staff_branch()->first()?->id ?? null;
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'name' => $this->name,
            'branch_id' => $this->branch_id,
            'country' => $this->country,
            'phone' => $this->phone,
            'tckimlik' => $this->tckimlik,
            'birth_date' => $this->birth_date,
            'il' => $this->il,
            'ilce' => $this->ilce,
            'gender' => $this->gender,
            'adres' => $this->adres,
            'email' => $this->email,
            'user_id' => auth()->user()->id,
            'permission' => PermissionType::action_client_create,
        ], [
            'name' => 'required',
            'branch_id' => 'required|exists:branches,id',
            'country' => 'required',
            'phone' => 'required|digits:10|unique:users,phone',
            'tckimlik' => 'nullable|digits:11',
            'birth_date' => 'nullable|before:now',
            'il' => 'nullable',
            'ilce' => 'nullable',
            'gender' => 'required',
            'adres' => 'nullable',
            'email' => 'nullable|email',
            'user_id' => 'required',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateClientAction::run($validator->validated());

        $this->success('Danışan oluşturuldu.');
        $this->close();

    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-client');
    }
}
