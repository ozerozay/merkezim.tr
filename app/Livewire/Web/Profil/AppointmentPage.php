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
    public bool $show_stats = true;

    protected $listeners = [
        'refresh-client-appointments' => '$refresh',
    ];

    public function mount(): void
    {
        try {
            $this->getSettings();
            $this->create_appointment = $this->getCollection(SettingsType::client_page_appointment_create->name);
            $this->statuses = $this->getCollection(SettingsType::client_page_appointment_show->name);
            $this->show_services = $this->getBool(SettingsType::client_page_appointment_show_services->name);
            $this->show_stats = $this->getBool(SettingsType::client_page_appointment_show_stats->name);
        } catch (\Throwable $e) {
            $this->error(__('messages.try_again'));
        }
    }

    public function render()
    {
        return view('livewire.client.profil.appointment-page', [
            'data' => GetPageAppointmentAction::run($this->statuses),
        ]);
    }
}
