<?php

namespace App\Actions\Spotlight\Actions\Settings;

use App\Models\Settings;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSettingsAction
{
    use AsAction;

    public function handle($key)
    {
        try {
            $settings = \Cache::rememberForever('tenant.'.tenant()->id.'.branch_settings'.auth()->user()->branch_id, function () {
                return Settings::where('branch_id', auth()->user()->branch_id)->first();
            });

            return $settings->data[$key];
        } catch (\Throwable $e) {
            return '';
        }

    }
}
