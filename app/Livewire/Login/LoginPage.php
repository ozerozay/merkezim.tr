<?php

namespace App\Livewire\Login;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Models\Branch;
use App\Models\User;
use Livewire\Attributes\Locked;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class LoginPage extends SlideOver
{
    use Toast;

    #[Locked]
    public $section = 'phone';

    public $phone = '';

    public $code = '';

    public $gender = 1;

    public $name = null;

    public $branches;

    public $branch;

    public $otp;

    public $ti = true;

    public $kvk = true;

    public bool $redirectOrDispatch = true;

    public function mount(bool $redirectOrDispatch = true)
    {
        if (\Auth::check()) {
            $this->close();
            $this->redirectIntended('/');
        }

        $this->redirectOrDispatch = $redirectOrDispatch;

        $this->branches = Branch::select('id', 'active', 'name')->where('active', true)->get();

        $this->branch = $this->branches->first()?->id ?? null;
    }

    public function backToPhone()
    {
        $this->section = 'phone';
    }

    public function submitForm()
    {
        try {
            $validator = \Validator::make([
                'phone' => $this->phone,
                'code' => $this->code,
                'name' => $this->name,
                'gender' => $this->gender,
                'branch' => $this->branch,
                'ti' => $this->ti,
                'kvk' => $this->kvk,
            ], [
                'phone' => 'required|digits:10',
                'code' => 'required|digits:4',
                'name' => 'required',
                'gender' => 'required',
                'branch' => 'required|exists:branches,id',
                'ti' => 'boolean',
                'kvk' => ['required', 'boolean', 'accepted'],

            ]);

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            $user = User::query()
                ->where('phone', $this->phone)
                ->where('phone_code', $this->code)
                ->withTrashed()
                ->first();

            if (! $user) {
                $this->error('Kullanıcı bulunamadı.');

                return;
            }

            $user->restore();

            $user->name = $this->name;
            $user->gender = $this->gender;
            $user->branch_id = $this->branch;
            $user->save();

            auth()->login($user, true);
            request()->session()->regenerate();

            if ($this->redirectOrDispatch) {
                $this->redirectIntended('/');
            } else {
                $this->dispatch('client-logged-in');
                $this->close();

            }

        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }

    public function submitCode()
    {

        try {

            $validator = \Validator::make([
                'phone' => $this->phone,
                'code' => $this->code,
            ], [
                'phone' => 'required|digits:10',
                'code' => 'required|digits:4',
            ]);

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            $user = User::query()
                ->select('id', 'phone', 'name', 'phone_code', 'can_login')
                ->where('phone', $this->phone)
                ->where('phone_code', $this->code)
                ->withTrashed()
                ->first();

            if (! $user) {
                $this->error('Hatalı kod.');

                return;
            }

            if ($user->name == null) {
                $this->section = 'form';

                return;
            } else {

                if ($user->trashed()) {
                    $user->restore();
                }

                auth()->login($user, true);
                request()->session()->regenerate();

                if ($this->redirectOrDispatch) {
                    $this->redirectIntended('/');
                } else {
                    $this->dispatch('client-logged-in');
                    $this->close();
                }

            }

        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.'.$e->getMessage());
        }
    }

    public function submit_phone()
    {
        try {
            $validator = \Validator::make([
                'phone' => $this->phone,
            ], [
                'phone' => 'required|digits:10',
            ]);

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            $user = User::query()
                ->select('id', 'phone', 'phone_code', 'can_login')
                ->where('phone', $this->phone)
                ->withTrashed()
                ->first();

            if ($user) {
                if (! $user->can_login) {
                    $this->error('Kullanıcı bulunamadı.');

                    return;
                }

                $user->phone_code = 1111; //rand(100000, 999999);
                $user->save();
            } else {

                $user = User::create([
                    'branch_id' => Branch::where('active', true)->first()->id,
                    'unique_id' => CreateUniqueID::run('user'),
                    'country' => '90',
                    'phone' => $this->phone,
                    'phone_code' => 1111, // rand(100000, 999999),
                ]);

                $user->delete();
            }

            $this->section = 'code';
        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.'.$e->getMessage());
        }

    }

    public function render()
    {
        return view('livewire.login.login-page');
    }
}
