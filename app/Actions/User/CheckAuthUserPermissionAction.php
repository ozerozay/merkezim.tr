<?php

namespace App\Actions\User;

use App\Exceptions\AppException;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckAuthUserPermissionAction
{
    use AsAction;

    public function handle($can)
    {
        auth()->user()->can($can) ? '' : throw new AppException('Yetkiniz bulunmuyor.');
    }
}
