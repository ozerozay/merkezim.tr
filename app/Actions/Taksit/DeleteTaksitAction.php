<?php

namespace App\Actions\Taksit;

use App\Exceptions\AppException;
use App\Models\ClientTaksit;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class DeleteTaksitAction
{
    use AsAction;

    public function handle($info)
    {
        try {
            DB::beginTransaction();

            ClientTaksit::where('id', $info['id'])->delete();

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
