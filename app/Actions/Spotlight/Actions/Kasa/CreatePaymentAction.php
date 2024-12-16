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

    public function handle($info, $approve = false)
    {
        return Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $kasa = Kasa::select(['id', 'name', 'branch_id'])->where('id', $info['kasa_id'])->first();

            if (! $info['paid']) {
                $info['price'] *= -1;
            }

            $transaction_ids = [];

            switch ($info['type']) {
                case 'masraf':
                    $ts = Transaction::create([
                        'kasa_id' => $kasa->id,
                        'branch_id' => $kasa->branch_id,
                        'user_id' => $info['user_id'],
                        'date' => $info['date'],
                        'price' => $info['price'],
                        'message' => $info['message'],
                        'masraf_id' => $info['masraf_id'],
                        'type' => TransactionType::payment_masraf,
                    ]);
                    $transaction_ids[] = $ts->id;
                    break;
                case 'client':
                    $client = User::query()
                        ->select('id', 'name')
                        ->where('id', $info['client_id'])
                        ->first();

                    $ts = $client->client_transactions()->create([
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
                    $transaction_ids[] = $ts->id;
                    break;
                case 'staff':
                    $staff = User::query()
                        ->select('id', 'name')
                        ->where('id', $info['staff_id'])
                        ->first();

                    $ts = $staff->client_transactions()->create([
                        'kasa_id' => $kasa->id,
                        'branch_id' => $kasa->branch_id,
                        'user_id' => $info['user_id'],
                        'date' => $info['date'],
                        'price' => $info['price'],
                        'message' => $info['message'],
                        'masraf_id' => $info['masraf_id'],
                        'type' => TransactionType::payment_staff,
                    ]);
                    $transaction_ids[] = $ts->id;
                    break;
            }

            \DB::commit();

            return $transaction_ids;
        }, $approve);
    }
}
