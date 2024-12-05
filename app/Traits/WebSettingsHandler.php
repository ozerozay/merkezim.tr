<?php

namespace App\Traits;

trait WebSettingsHandler
{
    public function getBool($enum)
    {
        return (bool) \App\Actions\Spotlight\Actions\Settings\GetSettingsAction::run($enum);
    }
}
