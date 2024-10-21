<?php

namespace App\Actions\Helper;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUserUniqueID
{
    use AsAction;

    public function handle()
    {
        do {
            $code = random_int(100000000, 999999999);
        } while (User::select('unique_id')->where('unique_id', '=', $code)->exists());

        return $code;
    }
}
