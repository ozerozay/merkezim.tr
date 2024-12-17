<?php

namespace App\Actions\Spotlight\Actions\Get;

use Lorisleiva\Actions\Concerns\AsAction;

class GetUserAllPermissions
{
    use AsAction;

    public function handle()
    {
        try {
            $permissions = \Cache::rememberForever('tenant.'.tenant()->id.'.permissions.'.auth()->user()->id, function () {
                return auth()->user()->getAllPermissions()->pluck('name');
            });

            return collect($permissions);
        } catch (\Throwable $e) {
            return collect([]);
        }
    }
}
