<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Exceptions\AppException;
use App\Peren;
use App\SaleStatus;
use App\TransactionType;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTahsilatAction
{
    use AsAction;

    public function handle($info): void
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $info['price'] = (float) $info['price'];

            $client = \App\Models\User::query()
                ->select(['id', 'branch_id'])
                ->where('id', $info['client_id'])
                ->first();

            $totalDebt = $client->totalDebt();

            throw_if($info['price'] > $totalDebt, new AppException($totalDebt.' TL tahsilat alabilirsiniz.'));

            $activeTaksits = $client->clientTaksits()
                ->where('status', SaleStatus::success)
                ->where('remaining', '>', 0)
                ->orderBy('date', 'asc')
                ->get();

            $totalPrice = $info['price'];

            foreach ($activeTaksits as $taksit) {
                if ($totalPrice > 0) {
                    if ($taksit->remaining > $totalPrice) {
                        if ($taksit->sale) {
                            $taksit->sale->transactions()->create([
                                'kasa_id' => $info['kasa_id'],
                                'branch_id' => $client->branch_id,
                                'user_id' => $info['user_id'],
                                'client_id' => $client->id,
                                'date' => $info['date'],
                                'price' => $totalPrice,
                                'message' => $taksit->sale->sale_no.' nolu sözleşmeye yapılan tahsilat.',
                                'type' => TransactionType::tahsilat,
                            ]);
                        } else {
                            $taksit->transactions()->create([
                                'kasa_id' => $info['kasa_id'],
                                'branch_id' => $client->branch_id,
                                'user_id' => $info['user_id'],
                                'client_id' => $client->id,
                                'date' => $info['date'],
                                'price' => $totalPrice,
                                'message' => $taksit->date_human.' tarihli taksit için yapılan tahsilat.',
                                'type' => TransactionType::tahsilat_taksit,
                            ]);
                        }
                        $taksit->remaining -= $totalPrice;
                        $taksit->save();
                        $totalPrice = 0;
                        break;
                    } else {
                        if ($taksit->sale) {
                            $taksit->sale->transactions()->create([
                                'kasa_id' => $info['kasa_id'],
                                'branch_id' => $client->branch_id,
                                'user_id' => $info['user_id'],
                                'client_id' => $client->id,
                                'date' => $info['date'],
                                'price' => $taksit->remaining,
                                'message' => $taksit->sale->sale_no.' nolu sözleşmeye yapılan tahsilat.',
                                'type' => TransactionType::tahsilat,
                            ]);
                        } else {
                            $taksit->transactions()->create([
                                'kasa_id' => $info['kasa_id'],
                                'branch_id' => $client->branch_id,
                                'user_id' => $info['user_id'],
                                'client_id' => $client->id,
                                'date' => $info['date'],
                                'price' => $taksit->remaining,
                                'message' => $taksit->date_human.' tarihli taksit için yapılan tahsilat.',
                                'type' => TransactionType::tahsilat_taksit,
                            ]);
                        }
                        $totalPrice -= $taksit->remaining;
                        $taksit->remaining = 0;
                        $taksit->save();
                    }
                }

            }
        });
    }
}
