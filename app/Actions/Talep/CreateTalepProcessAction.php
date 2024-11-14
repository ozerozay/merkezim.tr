<?php

namespace App\Actions\Talep;

use App\Exceptions\AppException;
use App\Models\Talep;
use App\Models\TalepProcess;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateTalepProcessAction
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            TalepProcess::create($info);

            Talep::where('id', $info['talep_id'])->update([
                'status' => $info['status'],
            ]);

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
