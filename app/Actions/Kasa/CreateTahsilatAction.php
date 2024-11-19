<?php

namespace App\Actions\Kasa;

use App\Exceptions\AppException;
use App\SaleStatus;
use App\TransactionType;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateTahsilatAction
{
    use AsAction;

    public function handle($info): void
    {
        try {

            DB::beginTransaction();

            // ÖNCE DANIŞANI BUL

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
                        dump('buırda');
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
                        dump($totalPrice);
                        $taksit->remaining = 0;
                        $taksit->save();
                    }
                }

            }

            DB::commit();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage().$e->getLine());
        }
    }
}
