<?php

namespace App\Livewire\Web\Profil;

use App\Actions\Spotlight\Actions\Web\GetPageOfferAction;
use App\Enum\SettingsType;
use App\Traits\WebSettingsHandler;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('components.layouts.client')]
#[Title('Tekliflerim')]
class OfferPage extends Component
{
    use Toast, WebSettingsHandler;

    public bool $show_request = false;

    public function mount()
    {
        try {
            $this->getSettings();

            $this->show_request = $this->getBool(SettingsType::client_page_offer_request->name);

        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.');
        }
    }

    public function render()
    {
        return view('livewire.client.profil.offer-page', [
            'data' => GetPageOfferAction::run(),
        ]);
    }
}
