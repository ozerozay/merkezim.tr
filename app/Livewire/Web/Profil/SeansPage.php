<?php

namespace App\Livewire\Web\Profil;

use App\Actions\Spotlight\Actions\Web\GetPageSeansAction;
use App\Enum\SettingsType;
use App\Traits\WebSettingsHandler;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('components.layouts.client')]
#[Title('Seanslarım')]
#[Lazy()]
class SeansPage extends Component
{
    use Toast, WebSettingsHandler;

    public bool $show_zero = false;

    public bool $show_category = false;

    public bool $add_seans = false;

    public bool $show_stats = true;

    public $seans = [];

    public function mount()
    {
        try {
            $this->getSettings();

            $this->show_zero = $this->getBool(SettingsType::client_page_seans_show_zero->name);
            $this->show_category = $this->getBool(SettingsType::client_page_seans_show_category->name);
            $this->add_seans = $this->getBool(SettingsType::client_page_seans_add_seans->name);
            $this->show_stats = $this->getBool(SettingsType::client_page_seans_show_stats->name);
        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.');
        }
    }

    public function render()
    {
        return view('livewire.client.profil.seans-page', [
            'data' => GetPageSeansAction::run($this->show_category, $this->show_zero),
        ]);
    }
}
