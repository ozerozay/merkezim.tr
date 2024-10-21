<?php

namespace App\Actions\User;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckUserInstantApprove
{
    use AsAction;

    public function handle($user)
    {
        try {
            return User::select(['id', 'instant_approve'])->where('id', $user)->first()->instant_approve ?? false;
        } catch (\Throwable $e) {
            return false;
        }

    }
}
