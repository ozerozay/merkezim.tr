<?php

namespace App\Actions\User;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CheckUserPermissionAction
{
    use AsAction;

    public function handle($permission): void
    {
        try {
            throw_if(! auth()->user()->can($permission), new AppException('Yetkiniz bulunmuyor.'));
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
