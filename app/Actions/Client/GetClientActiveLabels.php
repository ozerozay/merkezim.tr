<?php

namespace App\Actions\Client;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientActiveLabels
{
    use AsAction;

    public function handle($client)
    {
        return User::query()
            ->select(['id', 'name', 'labels'])
            ->where('id', $client)
            ->with('client_labels')
            ->first();
    }
}
