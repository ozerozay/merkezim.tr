<?php

namespace App\Actions\Client;

use App\Models\User;
use App\Traits\StrHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateClientAction
{
    use AsAction, StrHelper;

    public function handle(array $info)
    {
        $info['name'] = $this->strUpper($info['name']);

        User::create($info);
    }
}
