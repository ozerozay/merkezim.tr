<?php

namespace App\Actions\Spotlight\Actions\User;

use App\Exceptions\AppException;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CheckUserPermission
{
    use AsAction;

    public function handle($permission): void
    {
        try {
            throw_if(! auth()->user()->can($permission), new AppException('Yetkiniz bulunmuyor.'));
        } catch (\Throwable $e) {
            throw ToastException::error('Yetkiniz bulunmuyor.');
        }
    }
}
