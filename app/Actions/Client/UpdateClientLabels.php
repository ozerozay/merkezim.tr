<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\User;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateClientLabels
{
    use AsAction;

    public function handle($info)
    {
        try {

            DB::beginTransaction();

            $client = User::select(['id', 'labels'])->where('id', $info['client_id'])->first();

            throw_if(! $client, new AppException('Danışan bulunamadı.'));

            $client->labels = $info['labels'];
            $client->save();

            DB::commit();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
