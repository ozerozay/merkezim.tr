<?php

namespace App\Actions\Client;

use App\Actions\Helper\CreateUserUniqueID;
use App\Models\User;
use App\Traits\StrHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateClientAction
{
    use AsAction, StrHelper;

    public function handle(array $info)
    {
        $info['name'] = $this->strUpper($info['name']);
        $info['unique_id'] = CreateUserUniqueID::run();

        User::create($info);
    }
}
