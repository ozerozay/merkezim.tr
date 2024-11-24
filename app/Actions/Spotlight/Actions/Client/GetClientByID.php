<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Exceptions\AppException;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientByID
{
    use AsAction;

    public function handle($unique_id, $id, $select = [], $throw = false)
    {
        $user = User::query()
            ->select(['id', 'name', 'unique_id', 'branch_id', 'gender', ...$select])
            ->where(function ($q) use ($unique_id, $id) {
                $q->where('unique_id', $unique_id)->orWhere('id', $id);
            })
            ->first();
        if ($throw) {
            throw_if(! $user, new AppException('Danışan bulunamadı.'));
        }

        return $user;
    }
}
