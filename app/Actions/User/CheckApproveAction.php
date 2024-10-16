<?php

namespace App\Actions\User;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckApproveAction
{
    use AsAction;

    public function handle($user_id)
    {
        $user = User::find($user_id);
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }
}
