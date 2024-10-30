<?php

namespace App\Actions\ClientService;

use App\Exceptions\AppException;
use App\Models\ClientService;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class DeleteClientServiceAction
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            ClientService::where('id', $info['id'])->delete();

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
