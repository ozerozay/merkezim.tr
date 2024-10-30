<?php

namespace App\Actions\Taksit;

use App\Exceptions\AppException;
use App\Models\ClientTaksit;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateTaksitDateAction
{
    use AsAction;

    public function handle($info)
    {
        try {
            DB::beginTransaction();

            $taksit = ClientTaksit::where('id', $info['id'])->first();

            $taksit->date = $info['date'];
            $taksit->save();

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
