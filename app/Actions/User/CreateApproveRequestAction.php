<?php

namespace App\Actions\User;

use App\ApproveStatus;
use App\Exceptions\AppException;
use App\Models\Approve;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateApproveRequestAction
{
    use AsAction;

    public function handle($info, $user, $type, $message)
    {
        try {

            DB::beginTransaction();

            Approve::create([
                'user_id' => $user,
                'type' => $type,
                'message' => $message,
                'status' => ApproveStatus::waiting,
                'data' => $info,
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
