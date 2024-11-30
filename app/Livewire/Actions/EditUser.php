<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\EditClientAction;
use App\Enum\PermissionType;
use App\Models\User;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class EditUser extends SlideOver
{
    use Toast;

    public int|User $client;

    public $name = '';

    public $country = '90';

    public $phone;

    public $tckimlik;

    public $birth_date;

    public $il = 40;

    public $ilce;

    public $gender = 1;

    public $adres;

    public $email;

    public $send_sms = true;

    public $can_login = true;

    public $ils = [];

    public $ilces = [];

    public function mount(User $client): void
    {
        $this->ils = \App\Models\Il::orderBy('il_adi', 'asc')->get();
        $this->ilces = \App\Models\Ilce::where('il_id', $this->il)->get();
        $this->client = $client;
        $this->fill($client);
    }

    public function updatedIl($value)
    {
        $this->ilces = \App\Models\Ilce::where('il_id', $value)->get();
        $this->ilce = $this->ilces->first()?->id ?? null;
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'name' => $this->name,
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
            'can_login' => $this->can_login,
            'send_sms' => $this->send_sms,
            'permission' => PermissionType::action_edit_user->name,
            'client_id' => $this->client->id,
        ], [
            'name' => 'required',
            'country' => 'required',
            'phone' => ['required', 'digits:10', Rule::unique('users', 'phone')->ignore($this->client->id)],
            'tckimlik' => 'nullable|digits:11',
            'birth_date' => 'nullable|before:now',
            'il' => 'nullable',
            'ilce' => 'nullable',
            'gender' => 'required',
            'adres' => 'nullable',
            'email' => 'nullable|email',
            'user_id' => 'required',
            'permission' => 'required',
            'send_sms' => 'required',
            'can_login' => 'required',
            'client_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        EditClientAction::run($validator->validated());

        $this->success('Bilgiler düzenlendi.');
        $this->close();

    }

    public function render()
    {
        return view('livewire.spotlight.actions.edit-user');
    }
}
