<?php

namespace App\Livewire\Client\Menu;

use App\Actions\Spotlight\Actions\Settings\GetAllSettingsAction;
use Illuminate\Support\Collection;
use Livewire\Component;

class ClientAuthMenu extends Component
{
    private ?Collection $allSettings;

    public function mount(): void
    {
        try {
            $this->allSettings = GetAllSettingsAction::run();
        } catch (\Throwable $e) {

        }

    }

    public function checkSetting($key): bool
    {
        return (bool) $this->allSettings[$key];
    }

    public function render()
    {
        return view('livewire.client.menu.client-auth-menu');
    }
}
