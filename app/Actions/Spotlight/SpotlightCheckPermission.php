<?php

namespace App\Actions\Spotlight;

use Lorisleiva\Actions\Concerns\AsAction;

class SpotlightCheckPermission
{
    use AsAction;

    public function handle($permission)
    {
        return auth()->user()->can($permission);
    }
}
