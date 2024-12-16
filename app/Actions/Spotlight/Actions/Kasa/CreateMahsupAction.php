<?php

namespace App\Actions\Spotlight\Actions\Kasa;

use App\Models\Mahsup;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateMahsupAction
{
    use AsAction;

    public function handle($info, $approve = false)
    {
        return Peren::runDatabaseTransactionApprove($info, function () use ($info) {
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

            \DB::commit();

            return [$transaction_giris->id, $transaction_cikis->id];

        }, $approve);
    }
}
