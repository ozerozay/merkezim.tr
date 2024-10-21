<?php

namespace App\Actions\User;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class UserBranchOrClientBranchAction
{
    use AsAction;

    public function handle($client_id)
    {
        $client = User::select(['id', 'branch_id'])->where('id', $client_id)->first();

        return $client ? [$client->branch_id] : auth()->user()->staff_branches;
    }
}
