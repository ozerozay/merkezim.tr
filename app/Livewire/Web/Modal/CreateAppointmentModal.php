<?php

namespace App\Livewire\Web\Modal;

use App\Actions\Spotlight\Actions\Check\CheckAvailableAppointments;
use App\Actions\Spotlight\Actions\Client\CreateAppointmentManuelAction;
use App\Actions\Spotlight\Actions\Client\Get\GetClientServiceCategory;
use App\Actions\Spotlight\Actions\User\RequestApproveAction;
use App\Enum\PermissionType;
use App\Enum\SettingsType;
use App\Models\Branch;
use App\Models\ClientService;
use App\Models\ServiceRoom;
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

    public ?bool $create_appointment_approve = true;

    public ?Collection $create_appointment_branches;

    public $step = 1;

    public $totalSteps = 6;  // Toplamda 6 adım olacak

    public ?Collection $branches;

    public ?Collection $serviceCategories;

    public ?Collection $services;

    public ?Collection $rooms;

    public $appointmentType = null;

    public $selectedBranch = null;

    public $selectedCategory = null;

    public $selectedServices = [];

    public $selectedRoom = null;

    public ?Collection $selectedServicesCollection;

    public $available_appointments_range = [];

    public $selectedDate = null;

    public $selected_available_date;

    public $appointmentMessage;

    public function mount(): void
    {
        try {

            $this->getSettings();
            $this->create_appointment = $this->getCollection(SettingsType::client_page_appointment_create->name);
            $this->once_category = $this->getBool(SettingsType::client_page_appointment_create_once_category->name);
            $this->create_appointment_approve = $this->getBool(SettingsType::client_page_appointment_create_appointment_approve->name);
            $this->create_appointment_branches = $this->getCollection(SettingsType::client_page_appointment_create_branches->name);
            $this->branches = collect();
            $this->serviceCategories = collect();
            $this->services = collect();
            $this->rooms = collect();
            $this->selectedServicesCollection = collect();

            // ŞUBE YOKSA
            // AYNI ANDA RANDEVUSU OLUP OLMADIĞI
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

    public function selectService(): void
    {
        $this->selectedServices = collect($this->selectedServices);
        if ($this->selectedServices->isEmpty() || $this->selectedServices->doesntContain(fn($value) => $value == true)) {
            $this->warning('Hizmet seçmelisiniz.');

            return;
        }

        if ($this->appointmentType == 'date') {
            if ($this->selectedRoom == null) {
                $this->warning('Oda seçmelisiniz.');

                return;
            }
        }

        $selectedServices = $this->selectedServices->keys();

        $this->selectedServicesCollection = ClientService::query()
            ->whereIn('id', $selectedServices)
            ->with('service:id,name,duration')
            ->get();

        $this->goToDate();
    }

    public function goToBranch(): void
    {
        if ($this->selectedBranch) {
            $this->getServiceCategories($this->selectedBranch);
            $this->goToCategory();
        } else {
            $this->step = 2;
        }
    }

    public function goToCategory(): void
    {
        if ($this->selectedCategory) {
            $this->getServices($this->selectedCategory);
            $this->getRooms($this->selectedCategory);
            $this->goToService();
        } else {
            $this->step = 3;
        }
    }

    public function goToService(): void
    {
        $this->selectedServices = [];
        $this->selectedRoom = null;
        $this->getServices($this->selectedCategory);
        $this->getRooms($this->selectedCategory);

        $this->step = 4;
    }

    public function goToDate(): void
    {
        $this->step = 5;
    }

    public function backToType(): void
    {
        $this->step = 1;
    }

    public function backToBranch(): void
    {
        if ($this->branches->count() > 1) {
            $this->step = 2;
        } else {
            $this->step = 1;
        }
    }

    public function backToCategory(): void
    {
        if ($this->serviceCategories->count() > 1) {
            $this->step = 3;
        } else {
            $this->step = 2;
        }
    }

    public function backToService(): void
    {
        $this->step = 4;
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

    public function createAppointmentManuel($info = []): void
    {
        try {
            $validator = \Validator::make(
                count($info) > 0 ? $info :
                    [
                        'client_id' => auth()->user()->id,
                        'category_id' => $this->selectedCategory,
                        'service_ids' => $this->selectedServices->filter(function ($value) {
                            return $value === true;
                        })->keys()->toArray(),
                        'date' => $this->selectedDate,
                        'room_id' => $this->selectedRoom,
                        'message' => $this->appointmentMessage,
                        'user_id' => auth()->user()->id,
                        'permission' => PermissionType::action_client_create_appointment->name,
                    ],
                [
                    'client_id' => 'required|exists:users,id',
                    'category_id' => 'required|exists:service_categories,id',
                    'service_ids' => 'required|array',
                    'date' => 'required|date|after:now',
                    'room_id' => 'required|exists:service_rooms,id',
                    'message' => 'required',
                    'user_id' => 'required|exists:users,id',
                    'permission' => 'required',
                ]
            );

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            if (! $this->create_appointment_approve) {
                RequestApproveAction::run($validator->validated(), auth()->user()->id, PermissionType::action_client_create_appointment->name, $this->appointmentMessage ?? '');

                $this->success('Randevu talebiniz onaylandığında bildirim alacaksınız.');
            } else {

                CreateAppointmentManuelAction::run($validator->validated(), false, true);

                $this->success('Randevunuz oluşturuldu.');
            }
            $this->close();
        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.');
        }
    }

    public function findAvaibleAppointmentsMulti(): void
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
                    $title = \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y') . ' - ' . $rangeDate['name'];
                    foreach ($rangeDate['gaps'] as $gap) {
                        $toSelect[$title][] = [
                            'id' => \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y') . '||' . $rangeDate['name'] . '||' . $gap,
                            'name' => $gap,
                        ];
                    }
                }
            }

            $firstElementKey = array_key_first($toSelect);

            if ($firstElementKey !== null && isset($toSelect[$firstElementKey][0]['id'])) {
                $firstId = $toSelect[$firstElementKey][0]['id'];
                $this->selected_available_date = $firstId;
            } else {
                $this->selected_available_date = null;
            }

            $this->available_appointments_range = $toSelect;
        } catch (\Throwable $e) {
            $this->error('Lütfen daha sonra tekrar deneyin.' . $e->getMessage());
        }
    }

    public function createAppointmentRange(): void
    {
        try {
            if (! $this->selected_available_date) {
                $this->error('Tarih seçin.');

                return;
            }
            $value = explode('||', $this->selected_available_date);

            $service_room_id = \App\Models\ServiceRoom::where('name', $value[1])->first()->id;
            $time_split = explode('-', $value[2]);
            $range_date = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $value[0] . ' ' . $time_split[0])->format('Y-m-d H:i');

            $this->createAppointmentManuel([
                'client_id' => auth()->user()->id,
                'category_id' => $this->selectedCategory,
                'service_ids' => $this->selectedServices->filter(function ($value) {
                    return $value === true;
                })->keys()->toArray(),
                'date' => $range_date,
                'room_id' => $service_room_id,
                'message' => $this->appointmentMessage,
                'user_id' => auth()->user()->id,
                'permission' => PermissionType::action_client_create_appointment->name,
            ]);
        } catch (\Throwable $e) {
            $this->error('Lütfen daha sonra tekrar deneyin.' . $e->getMessage());
        }
    }

    public function findAvaibleAppointmentsRange(): void
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

            //dump($this->selectedDate);

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
                    $title = \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y') . ' - ' . $rangeDate['name'];
                    foreach ($rangeDate['gaps'] as $gap) {
                        $toSelect[$title][] = [
                            'id' => \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('d/m/Y') . '||' . $rangeDate['name'] . '||' . $gap,
                            'name' => $gap,
                        ];
                    }
                }
            }

            $firstElementKey = array_key_first($toSelect);

            if ($firstElementKey !== null && isset($toSelect[$firstElementKey][0]['id'])) {
                $firstId = $toSelect[$firstElementKey][0]['id'];
                $this->selected_available_date = $firstId;
            } else {
                $this->selected_available_date = null;
            }

            $this->available_appointments_range = $toSelect;
        } catch (\Throwable $e) {
            $this->error('Lütfen daha sonra tekrar deneyin.' . $e->getMessage());
        }
    }

    public function getServices($category): void
    {
        $this->services = ClientService::query()
            ->where('client_id', auth()->user()->id)
            ->where('status', SaleStatus::success)
            ->where('remaining', '>', 0)
            ->whereHas('service.category', function ($q) use ($category) {
                $q->where('id', $category);
            })
            ->whereRelation('service', 'is_visible', '=', true)
            ->whereRelation('service', 'active', '=', true)
            ->with('service:name,id,category_id,duration', 'service.category:id,name', 'sale:id,unique_id')
            ->get();
    }

    public function getRooms($category): void
    {
        $this->rooms = ServiceRoom::query()
            ->where('branch_id', auth()->user()->branch_id)
            ->whereHas('categories', function ($q) use ($category) {
                $q->where('id', $category);
            })
            ->where('active', true)
            ->orderBy('name')
            ->get();
    }

    public function closeAndError($message = 'Lütfen tekrar deneyin.'): void
    {
        $this->error($message);
        $this->close();
    }

    public function toggleStep($step)
    {
        $this->step = $step; // Her bir adım arasında geçiş yapıyoruz
    }

    public function render()
    {
        return view('livewire.client.modal.create-appointment-modal');
    }
}
