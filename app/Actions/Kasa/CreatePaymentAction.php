<?php

namespace App\Actions\Kasa;

use App\Exceptions\AppException;
use App\Models\Kasa;
use App\Models\Transaction;
use App\Models\User;
use App\Peren;
use App\TransactionType;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreatePaymentAction
{
    use AsAction;

    /**
     * @throws ToastException
     */
    public function handle($info): void
    {
        try {

            DB::beginTransaction();

            foreach ($info as $payment) {
                $kasa = Kasa::select(['id', 'name', 'branch_id'])->where('id', $payment['kasa_id'])->first();

                if ($payment['type'] == 'Danışan') {
                    $client = User::query()
                        ->select('id', 'name')
                        ->where('id', $payment['client_id'])
                        ->first();

                    $client->client_transactions()->create([
                        'kasa_id' => $kasa->id,
                        'branch_id' => $kasa->branch_id,
                        'client_id' => $client->id,
                        'user_id' => $payment['user_id'],
                        'date' => Peren::parseDateField($payment['date']),
                        'price' => $payment['price'],
                        'message' => $payment['message'],
                        'masraf_id' => $payment['masraf_id'],
                        'type' => TransactionType::payment_client,
                    ]);
                } elseif ($payment['type'] == 'Personel') {
                    $staff = User::query()
                        ->select('id', 'name')
                        ->where('id', $payment['staff_id'])
                        ->first();

                    $staff->client_transactions()->create([
                        'kasa_id' => $kasa->id,
                        'branch_id' => $kasa->branch_id,
                        'user_id' => $payment['user_id'],
                        'date' => Peren::parseDateField($payment['date']),
                        'price' => $payment['price'],
                        'message' => $payment['message'],
                        'masraf_id' => $payment['masraf_id'],
                        'type' => TransactionType::payment_staff,
                    ]);
                } elseif ($payment['type'] == 'Masraf') {
                    Transaction::create([
                        'kasa_id' => $kasa->id,
                        'branch_id' => $kasa->branch_id,
                        'user_id' => $payment['user_id'],
                        'date' => Peren::parseDateField($payment['date']),
                        'price' => $payment['price'] * -1,
                        'message' => $payment['message'],
                        'masraf_id' => $payment['masraf_id'],
                        'type' => TransactionType::payment_masraf,
                    ]);
                }
            }

            DB::commit();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
