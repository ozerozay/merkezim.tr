<?php

namespace App\Livewire\Web\Modal;

use App\Actions\Spotlight\Actions\Check\CheckAvailableAppointments;
use App\Actions\Spotlight\Actions\Client\Get\GetClientServiceCategory;
use App\Enum\SettingsType;
use App\Models\Branch;
use App\Models\ClientService;
use App\Peren;
use App\SaleStatus;
use App\Traits\WebSettingsHandler;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateAppointmentModal extends SlideOver
{
    use Toast, WebSettingsHandler;

    public ?Collection $create_appointment;

    public ?bool $once_category = false;

    public ?Collection $create_appointment_branches;

    public $step = 1;

    public $totalSteps = 6;  // Toplamda 6 adım olacak

    public ?Collection $branches;

    public ?Collection $serviceCategories;

    public ?Collection $services;

    public $appointmentType = null;

    public $selectedBranch = null;

    public $selectedCategory = null;

    public $selectedServices = [];

    public ?Collection $selectedServicesCollection;

    public $available_appointments_range = [];

    public $selectedDate = null;

    public $selected_available_date;

    public function mount(): void
    {
        try {
            $this->getSettings();
            $this->create_appointment = $this->getCollection(SettingsType::client_page_appointment_create->name);
            $this->once_category = $this->getBool(SettingsType::client_page_appointment_create_once_category->name);
            $this->create_appointment_branches = $this->getCollection(SettingsType::client_page_appointment_create_branches->name);
            $this->branches = collect();
            $this->serviceCategories = collect();
            $this->services = collect();
            $this->selectedServicesCollection = collect();
        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.');
            $this->close();
        }

    }

    public function selectAppointmentType($type): void
    {
        $this->appointmentType = $type;
        $this->getBranches();

        if ($this->branches->count() == 1) {
            $this->selectedBranch = $this->branches->first()->id;
            $this->goToBranch();
        } elseif ($this->branches->count() > 1) {
            $this->selectedBranch = null;
            $this->goToBranch();
        } else {
            $this->closeAndError();
        }
    }

    public function selectBranch($branch): void
    {
        $this->selectedBranch = $branch;
        $this->getServiceCategories($branch);

        if ($this->serviceCategories->count() == 1) {
            $this->selectedCategory = $this->serviceCategories->first()->id;
            $this->goToCategory();
        } elseif ($this->serviceCategories->count() > 1) {
            $this->selectedCategory = null;
            $this->goToCategory();
        } else {
            $this->closeAndError();
        }
    }

    public function selectCategory($category): void
    {
        $this->selectedCategory = $category;

        $this->goToService();

    }

    public function selectService()
    {
        $this->selectedServices = collect($this->selectedServices);
        if ($this->selectedServices->isEmpty() || $this->selectedServices->doesntContain(fn ($value) => $value == true)) {
            $this->warning('Hizmet seçmelisiniz.');

            return;
        }

        $selectedServices = $this->selectedServices->keys();

        $this->selectedServicesCollection = ClientService::query()
            ->whereHas('service', function ($q) use ($selectedServices) {
                $q->whereIn('id', $selectedServices);
            })
            ->with('service:id,name,duration')
            ->get();

        $this->goToDate();

    }

    public function goToBranch(): void
    {
        if ($this->selectedBranch) {
            $this->goToCategory();
        } else {
            $this->step = 2;
        }
    }

    public function goToCategory(): void
    {
        if ($this->selectedCategory) {
            $this->getServices($this->selectedCategory);
            $this->goToService();
        } else {
            $this->step = 3;
        }
    }

    public function goToService(): void
    {
        $this->step = 4;
    }

    public function goToDate(): void
    {
        $this->step = 5;
    }

    public function getBranches(): void
    {
        $this->branches = Branch::query()
            ->where('active', true)
            ->whereIn('id', $this->create_appointment_branches->isNotEmpty() ? $this->create_appointment_branches->toArray() : [auth()->user()->branch_id])
            ->orderBy('name')
            ->get();
    }

    public function getServiceCategories($branch): void
    {
        $this->serviceCategories = GetClientServiceCategory::run(auth()->user()->id, $branch);
    }

    #[Computed]
    public function getTotalDuration()
    {
        return $this->selectedServicesCollection->sum(function ($q) {
            return $q->service->duration;
        });
    }

    public function createAppointmentManuel(): void
    {
        $validator = \Illuminate\Support\Facades\Validator::make(
            [
                'client_id' => auth()->user()->id,
                'category_id' => $this->selectedCategory,
                'service_ids' => $this->selectedServices,
                'date' => $this->date,
                'message' => $this->message,
                'user_id' => auth()->user()->id,
            ], [
                'client_id' => 'required|exists:users,id',
                'category_id' => 'required|exists:service_categories,id',
                'service_ids' => 'required|array',
                'date' => 'required|date|after:now',
                'message' => 'required',
                'user_id' => 'required|exists:users,id',
            ]
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }
    }

    public function createAppointmentMulti(): void
    {
        try {

            if ($this->selectedServicesCollection->isempty()) {
                $this->error('Hizmet seçmelisiniz.');

                return;
            }
            if (! $this->selectedDate) {
                $this->error('Tarih seçmelisiniz.');

                return;
            }

            $duration = $this->getTotalDuration();

            if ($duration < 1) {
                $this->error('Süre hesaplanamadı.');

                return;
            }

            $client = \App\Models\User::query()->where('id', auth()->user()->id)->first();

            $dates = collect(explode(',', $this->selectedDate))->map(function ($q) {
                $q = trim($q);
                $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $q);

                return $date->format('Y-m-d');
            });

            $info = [
                'branch_id' => $client->branch_id,
                'category_id' => $this->selectedCategory,
                'duration' => $duration,
                'type' => 'multiple',
                'dates' => $dates->toArray(),
            ];

            $available_appointments_range = CheckAvailableAppointments::run($info);

            $toSelect = [];

            foreach ($available_appointments_range as $key => $dates) {
                foreach ($dates as $rangeDate) {
                    $title = \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y').' - '.$rangeDate['name'];
                    foreach ($rangeDate['gaps'] as $gap) {
                        $toSelect[$title][] = [
                            'id' => \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y').'||'.$rangeDate['name'].'||'.$gap,
                            'name' => $gap,
                        ];
                    }
                }
            }

            $this->available_appointments_range = $toSelect;

        } catch (\Throwable $e) {
            $this->error('Lütfen daha sonra tekrar deneyin.'.$e->getMessage());
        }
    }

    public function createAppointmentRange(): void
    {
        try {
            if ($this->selectedServicesCollection->isempty()) {
                $this->error('Hizmet seçmelisiniz.');

                return;
            }
            if (! $this->selectedDate) {
                $this->error('Tarih seçmelisiniz.');

                return;
            }

            $duration = $this->getTotalDuration();

            if ($duration < 1) {
                $this->error('Süre hesaplanamadı.');

                return;
            }

            $client = \App\Models\User::query()->where('id', auth()->user()->id)->first();

            $format_range = Peren::formatRangeDate($this->selectedDate);

            $info = [
                'branch_id' => $client->branch_id,
                'search_date_first' => $format_range['first_date'],
                'search_date_last' => $format_range['last_date'],
                'category_id' => $this->selectedCategory,
                'duration' => $duration,
                'type' => 'range',
            ];

            $available_appointments_range = CheckAvailableAppointments::run($info);

            $toSelect = [];

            foreach ($available_appointments_range as $key => $dates) {
                foreach ($dates as $rangeDate) {
                    $title = \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y').' - '.$rangeDate['name'];
                    foreach ($rangeDate['gaps'] as $gap) {
                        $toSelect[$title][] = [
                            'id' => \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y').'||'.$rangeDate['name'].'||'.$gap,
                            'name' => $gap,
                        ];
                    }
                }
            }

            $this->available_appointments_range = $toSelect;
        } catch (\Throwable $e) {
            $this->error('Lütfen daha sonra tekrar deneyin.');
        }
    }

    public function getServices($category): void
    {
        $this->services = ClientService::selectRaw('id, client_id, service_id,status ,SUM(remaining) as remaining')
            ->where('client_id', auth()->user()->id)
            ->where('status', SaleStatus::success)
            ->where('remaining', '>', 0)
            ->whereRelation('service', 'is_visible', '=', true)
            ->whereRelation('service', 'active', '=', true)
            ->with('service:name,id,category_id,duration', 'service.category:id,name', 'sale:id,unique_id')
            ->groupBy('service_id')
            ->get();
    }

    public function closeAndError($message = 'Lütfen tekrar deneyin.'): void
    {
        $this->error($message);
        $this->close();
    }

    public function render()
    {
        return view('livewire.client.modal.create-appointment-modal');
    }
}
