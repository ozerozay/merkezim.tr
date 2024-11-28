<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\CreateCouponAction;
use App\Actions\Spotlight\Actions\Create\CreateCouponCode;
use App\Enum\PermissionType;
use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateCoupon extends SlideOver
{
    use Toast;

    public int|User $client;

    public $code;

    public $discount_type = true;

    public $count = 1;

    public $discount_amount = 1;

    public $end_date;

    public $min_order;

    public $discount_types;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->discount_types = [
            ['id' => true, 'name' => 'YÜZDE'],
            ['id' => false, 'name' => 'TL'],
        ];
    }

    public function generateCode(): void
    {
        $this->code = CreateCouponCode::run();
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'client_id' => $this->client->id,
            'code' => $this->code,
            'discount_type' => $this->discount_type,
            'count' => $this->count,
            'discount_amount' => $this->discount_amount,
            'end_date' => $this->end_date,
            'min_order' => $this->min_order,
            'user_id' => auth()->user()->id,
            'permission' => PermissionType::action_create_coupon->name,
        ], [
            'client_id' => 'required|exists:users,id',
            'code' => 'required|unique:coupons,code|max:20',
            'discount_type' => 'required|boolean',
            'count' => 'required|integer',
            'discount_amount' => 'required|min:1',
            'end_date' => 'nullable|before:today',
            'min_order' => 'nullable|min:1',
            'user_id' => 'required|exists:users,id',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateCouponAction::run($validator->validated());

        $this->success('Kupon oluşturuldu.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-coupon');
    }
}
