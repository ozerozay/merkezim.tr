<?php

namespace App\Actions\Client;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientByUniqueID
{
    use AsAction;

    public function handle($unique_id, $id)
    {
        return User::query()
            ->select(['id', 'name', 'unique_id', 'branch_id', 'gender'])
            ->where(function ($q) use ($unique_id, $id) {
                $q->where('unique_id', $unique_id)->orWhere('id', $id);
            })
            ->first();
    }
}
