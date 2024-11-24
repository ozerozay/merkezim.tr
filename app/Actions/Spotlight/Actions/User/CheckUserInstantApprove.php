<?php

namespace App\Actions\Spotlight\Actions\User;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckUserInstantApprove
{
    use AsAction;

    public function handle($user, $permission): bool
    {
        try {
            $user = User::query()
                ->select(['id', 'instant_approve', 'instant_approves'])
                ->where('id', $user)
                ->with('staffInstantApproves')
                ->first();

            if (! $user) {
                return false;
            }

            if ($user->hasRole('admin')) {
                return true;
            }

            if ($user->instant_approve) {
                return true;
            }

            if ($user->staffInstantApproves()->where('name', $permission)->exists()) {
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
