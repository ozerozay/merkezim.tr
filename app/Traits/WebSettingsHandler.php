<?php

namespace App\Traits;

use App\Actions\Spotlight\Actions\Settings\GetAllSettingsAction;
use Illuminate\Support\Collection;

trait WebSettingsHandler
{
    public ?Collection $settings;

    public function getSettings(): void
    {
        $this->settings = GetAllSettingsAction::run();
    }

    public function getBool($enum): bool
    {
        return (bool) $this->settings->get($enum) ?? false;
    }

    public function getCollection($name): Collection
    {
        return collect($this->settings->get($name) ?? []);
    }

    public function placeholder()
    {
        return view('livewire.components.card.loading.loading');
    }
}
