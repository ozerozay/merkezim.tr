<?php

namespace App\Actions\Spotlight\Actions\Settings;

use Lorisleiva\Actions\Concerns\AsAction;

class GetGeneralSettings
{
    use AsAction;

    public function handle()
    {
        try {
            $settings = \Cache::rememberForever('tenant.'.tenant()->id.'.gsettings', function () {
                return \App\Models\Settings::whereNull('branch_id')->toArray()['data'];
            });

            return collect($settings);
        } catch (\Throwable $e) {
            return collect([]);
        }
    }
}
