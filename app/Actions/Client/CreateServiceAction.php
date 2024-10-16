<?php

namespace App\Actions\Client;

use App\Models\ClientService;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateServiceAction
{
    use AsAction;

    public function handle($info)
    {
        return ClientService::create($info);
    }
}
