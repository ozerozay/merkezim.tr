<?php

namespace App\Actions\Spotlight\Actions\Kasa;

use App\Models\Kasa;
use App\Models\Transaction;
use App\Models\User;
use App\Peren;
use App\TransactionType;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePaymentAction
{
    use AsAction;

    public function handle($info, $paid = false)
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info, $paid) {
            $kasa = Kasa::select(['id', 'name', 'branch_id'])->where('id', $info['kasa_id'])->first();

            if (! $paid) {
                $info['price'] *= -1;
            }

            switch ($info['type']) {
                case 'masraf':
                    Transaction::create([
                        'kasa_id' => $kasa->id,
                        'branch_id' => $kasa->branch_id,
                        'user_id' => $info['user_id'],
                        'date' => $info['date'],
                        'price' => $info['price'],
                        'message' => $info['message'],
                        'masraf_id' => $info['masraf_id'],
                        'type' => TransactionType::payment_masraf,
                    ]);
                    break;
                case 'client':
                    $client = User::query()
                        ->select('id', 'name')
                        ->where('id', $info['client_id'])
                        ->first();

                    $client->client_transactions()->create([
                        'kasa_id' => $kasa->id,
                        'branch_id' => $kasa->branch_id,
                        'client_id' => $client->id,
                        'user_id' => $info['user_id'],
                        'date' => $info['date'],
                        'price' => $info['price'],
                        'message' => $info['message'],
                        'masraf_id' => $info['masraf_id'],
                        'type' => TransactionType::payment_client,
                    ]);
                    break;
                case 'staff':
                    $staff = User::query()
                        ->select('id', 'name')
                        ->where('id', $info['staff_id'])
                        ->first();

                    $staff->client_transactions()->create([
                        'kasa_id' => $kasa->id,
                        'branch_id' => $kasa->branch_id,
                        'user_id' => $info['user_id'],
                        'date' => $info['date'],
                        'price' => $info['price'],
                        'message' => $info['message'],
                        'masraf_id' => $info['masraf_id'],
                        'type' => TransactionType::payment_staff,
                    ]);
                    break;
            }
        });
    }
}
