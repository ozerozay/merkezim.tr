<?php

namespace App\Actions\Client;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientBranch
{
    use AsAction;

    public function handle($client)
    {
        return User::query()
            ->select(['id', 'branch_id'])
            ->where('id', $client)
            ->first()->branch_id ?? null;
    }
}
