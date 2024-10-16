<?php

namespace App\Actions\User;

use App\Exceptions\AppException;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckClientBranchAction
{
    use AsAction;

    public function handle(int $client_id)
    {
        try {
            if (! auth()->user()->whereHas('staff_branch', function ($q) use ($client_id) {
                $q->where('id', User::find($client_id)->branch_id);
            })->exists()) {
                throw new AppException('Bu danışan için yetkiniz bulunmuyor.');
            }
        } catch (\Throwable $e) {
            return throw new AppException('Bu danışan için yetkiniz bulunmuyor.');
        }
    }
}
