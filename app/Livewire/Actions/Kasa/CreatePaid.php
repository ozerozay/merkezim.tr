<?php

namespace App\Livewire\Actions\Kasa;

use App\Actions\Spotlight\Actions\Kasa\CreatePaymentAction;
use App\Enum\PermissionType;
use App\Rules\PriceValidation;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreatePaid extends SlideOver
{
    use Toast;

    public $payment_date;

    public $kasa_id;

    public $staff_id;

    public $masraf_id;

    public $message;

    public $price;

    public $selectedTab = 'masraf';

    public function mount()
    {
        $this->payment_date = date('Y-m-d');
    }

    public function masrafSave()
    {
        $validator = \Validator::make([
            'date' => $this->payment_date,
            'kasa_id' => $this->kasa_id,
            'message' => $this->message,
            'masraf_id' => $this->masraf_id,
            'price' => $this->price,
            'type' => 'masraf',
            'user_id' => auth()->user()->id,
            'permission' => PermissionType::kasa_make_payment,
        ], [
            'date' => 'required',
            'kasa_id' => 'required|exists:kasas,id',
            'message' => 'required',
            'masraf_id' => 'required|exists:masraf,id',
            'price' => ['required', new PriceValidation, 'min:1'],
            'type' => 'required',
            'user_id' => 'required',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreatePaymentAction::run($validator->validated(), true);

        $this->success('Ödeme alındı.');
    }

    public function staffSave()
    {

        $validator = \Validator::make([
            'date' => $this->payment_date,
            'kasa_id' => $this->kasa_id,
            'message' => $this->message,
            'staff_id' => $this->staff_id,
            'masraf_id' => $this->masraf_id,
            'price' => $this->price,
            'type' => 'staff',
            'user_id' => auth()->user()->id,
            'permission' => PermissionType::kasa_make_payment,
        ], [
            'date' => 'required',
            'kasa_id' => 'required|exists:kasas,id',
            'staff_id' => 'required|exists:users,id',
            'message' => 'required',
            'masraf_id' => 'required|exists:masraf,id',
            'price' => ['required', new PriceValidation, 'min:1'],
            'type' => 'required',
            'user_id' => 'required',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreatePaymentAction::run($validator->validated(), true);

        $this->success('Ödeme alındı.');
    }

    public function clientSave()
    {

        $validator = \Validator::make([
            'date' => $this->payment_date,
            'kasa_id' => $this->kasa_id,
            'message' => $this->message,
            'client_id' => $this->client_id,
            'masraf_id' => $this->masraf_id,
            'price' => $this->price,
            'type' => 'client',
            'user_id' => auth()->user()->id,
            'permission' => PermissionType::kasa_make_payment,
        ], [
            'date' => 'required',
            'kasa_id' => 'required|exists:kasas,id',
            'client_id' => 'required|exists:users,id',
            'message' => 'required',
            'masraf_id' => 'required|exists:masraf,id',
            'price' => 'required|decimal:0,2',
            'type' => 'required',
            'user_id' => 'required',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreatePaymentAction::run($validator->validated(), true);

        $this->success('Ödeme alındı.');
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

    public function render()
    {
        return view('livewire.spotlight.actions.kasa.create-paid');
    }
}
