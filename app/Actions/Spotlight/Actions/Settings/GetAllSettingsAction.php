<?php

namespace App\Actions\Spotlight\Actions\Settings;

use Lorisleiva\Actions\Concerns\AsAction;

class GetAllSettingsAction
{
    use AsAction;

    public function handle()
    {
        try {
            $settings = \Cache::rememberForever('tenant.'.tenant()->id.'.settings'.auth()->user()->branch_id, function () {
                return \App\Models\Settings::where('branch_id', auth()->user()->branch_id)->first()->toArray()['data'];
            });

            return collect($settings);
        } catch (\Throwable $e) {
            return [];
        }
    }
}
