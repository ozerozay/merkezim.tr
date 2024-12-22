<?php

namespace App\Livewire\Web\Profil;

use App\Actions\Spotlight\Actions\Web\GetPageAppointmentAction;
use App\Enum\SettingsType;
use App\Traits\WebSettingsHandler;
use Illuminate\Support\Collection;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Mary\Traits\Toast;

#[\Livewire\Attributes\Layout('components.layouts.client')]
#[\Livewire\Attributes\Title('Randevularım')]
#[Lazy()]
class AppointmentPage extends Component
{
    use Toast, WebSettingsHandler;

    public ?Collection $create_appointment;

    public ?Collection $statuses;

    public bool $show_services = false;

    public function mount(): void
    {
        try {
            $this->getSettings();
            $this->create_appointment = $this->getCollection(SettingsType::client_page_appointment_create->name);
            $this->statuses = $this->getCollection(SettingsType::client_page_appointment_show->name);
            $this->show_services = $this->getBool(SettingsType::client_page_appointment_show_services->name);
        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.'.$e->getMessage());
        }

    }

    public function render()
    {
        return view('livewire.client.profil.appointment-page', [
            'data' => GetPageAppointmentAction::run($this->statuses),
        ]);
    }
}
