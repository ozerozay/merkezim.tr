<?php

namespace App\Actions\Spotlight\Actions\Web\Payment;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Enum\PaymentStatus;
use App\Models\Payment;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateHavaleAction
{
    use AsAction;

    public function handle($info)
    {
        try {

            \DB::beginTransaction();

            $payment = Payment::create([
                'unique_id' => CreateUniqueID::run('payment'),
                'type' => $info['type'],
                'data' => $info,
                'payment_type' => 'havale',
                'status' => PaymentStatus::approved->name,
                'client_id' => $info['client_id'],
            ]);

            \DB::commit();

            return $payment;

        } catch (\Throwable $e) {
            \DB::rollBack();

            return false;
        }
    }
}
