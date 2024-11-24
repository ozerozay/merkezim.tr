<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckClientExists
{
    use AsAction;

    public function handle($client)
    {
        return User::where('id', $client)->select('id', 'name')->first();
    }
}
