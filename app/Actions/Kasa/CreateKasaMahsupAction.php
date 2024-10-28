<?php

namespace App\Actions\Kasa;

use App\Exceptions\AppException;
use App\Models\Mahsup;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateKasaMahsupAction
{
    use AsAction;

    public function handle($info)
    {
        try {

            DB::beginTransaction();

            $kasa_mahsup = Mahsup::create([
                'user_id' => $info['user_id'],
                'date' => $info['date'],
                'giris_kasa_id' => $info['giris_kasa_id'],
                'cikis_kasa_id' => $info['cikis_kasa_id'],
                'price' => $info['price'],
                'message' => $info['message'],
            ]);

            $transaction_giris = $kasa_mahsup->transactions()->create([
                'kasa_id' => $kasa_mahsup->giris_kasa_id,
                'branch_id' => $kasa_mahsup->giris_kasa->branch_id,
                'user_id' => $info['user_id'],
                'type' => 'mahsup',
                'date' => $info['date'],
                'price' => $info['price'],
                'message' => $info['message'],
            ]);

            $transaction_cikis = $kasa_mahsup->transactions()->create([
                'kasa_id' => $kasa_mahsup->cikis_kasa_id,
                'branch_id' => $kasa_mahsup->cikis_kasa->branch_id,
                'user_id' => $info['user_id'],
                'type' => 'mahsup',
                'date' => $info['date'],
                'price' => $info['price'] * -1,
                'message' => $info['message'],
            ]);

            $kasa_mahsup->transaction_giris_id = $transaction_giris->id;
            $kasa_mahsup->transaction_cikis_id = $transaction_cikis->id;
            $kasa_mahsup->save();

            DB::commit();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
