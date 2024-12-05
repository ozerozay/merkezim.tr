<?php

namespace App\Actions\Talep;

use App\Exceptions\AppException;
use App\Models\Talep;
use App\Traits\StrHelper;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class EditTalepAction
{
    use AsAction, StrHelper;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            $talep = Talep::where('id', $info['id'])->first();

            $talep->update([
                'name' => $this->strUpper($info['name']),
                'phone' => $info['phone'],
                'message' => $info['message'],
                'type' => $info['type'],
            ]);

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
