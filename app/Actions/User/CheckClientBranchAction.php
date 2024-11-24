<?php

namespace App\Actions\User;

use App\Exceptions\AppException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CheckClientBranchAction
{
    use AsAction;

    public function handle($client_id)
    {
        try {
            if ($client_id == null) {
                return true;
            }

            if (empty($client_id)) {
                return true;
            }

            if (! is_int($client_id)) {
                return true;
            }

            if (! auth()->user()->whereHas('staff_branch', function ($q) use ($client_id) {
                $q->where('id', User::find($client_id)->branch_id);
            })->exists()) {
                throw new AppException('Bu danışan için yetkiniz bulunmuyor.');
            }
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
