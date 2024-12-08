<?php

namespace App\Livewire\Web\Profil;

use App\Actions\Spotlight\Actions\Web\GetPageTaksitAction;
use App\Enum\SettingsType;
use App\Traits\WebSettingsHandler;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('components.layouts.client')]
#[Title('Taksitlerim')]
class TaksitPage extends Component
{
    use Toast, WebSettingsHandler;

    public bool $show_zero = false;

    public bool $show_pay = false;

    public bool $show_locked = false;

    public function mount()
    {
        try {
            $this->getSettings();

            $this->show_zero = $this->getBool(SettingsType::client_page_taksit_show_zero->name);
            $this->show_pay = $this->getBool(SettingsType::client_page_taksit_pay->name);
            $this->show_locked = $this->getBool(SettingsType::client_page_taksit_show_locked->name);
        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.');
        }
    }

    public function render()
    {
        return view('livewire.client.profil.taksit-page', [
            'data' => GetPageTaksitAction::run($this->show_zero),
        ]);
    }
}
