<?php

namespace App\Livewire\Web\Profil;

use App\Actions\Spotlight\Actions\Check\CheckAvailableAppointments;
use App\Actions\Spotlight\Actions\Client\Get\GetClientServiceCategory;
use App\Models\ClientService;
use App\Peren;
use App\SaleStatus;
use App\Traits\WebSettingsHandler;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('components.layouts.client')]
#[Lazy()]
class CreateAppointmentPage extends Component
{
    use Toast, WebSettingsHandler;

    public $step = 1;
    public $totalSteps = 4;

    // Kategori için
    public ?Collection $serviceCategories;
    public $selectedCategory = null;
    public $selectedCategoryName = '';

    // Servisler için
    public Collection $services;
    public $selectedServices = [];
    public $selectedServicesDetails = [];

    // Tarih için
    public $selectedDate = null;
    public $available_appointments_range = [];
    public $selected_available_date;
    public $appointmentMessage;

    // Toplam süre
    public $totalDuration = 0;

    public function mount(): void
    {
        $this->serviceCategories = GetClientServiceCategory::run([
            'client_id' => auth()->user()->id,
            'branch_id' => auth()->user()->branch_id
        ]);

        // Boş koleksiyon olarak başlat
        $this->services = collect();
    }

    public function selectCategory($categoryId, $categoryName): void
    {
        $this->selectedCategory = $categoryId;
        $this->selectedCategoryName = $categoryName;
        $this->getServices($categoryId);
        $this->step = 2;
    }

    public function getServices($category): void
    {
        $services = ClientService::query()
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

        // Eğer servis bulunamazsa hata göster
        if ($services->isEmpty()) {
            $this->error(__('client.error_no_services_found'));
            return;
        }

        $this->services = $services;
    }

    public function goToDateSelection(): void
    {
        if (empty($this->selectedServices)) {
            $this->error(__('client.error_service_required'));
            return;
        }

        // Seçilen servislerin detaylarını güncelle
        $this->selectedServicesDetails = $this->services
            ->whereIn('id', $this->selectedServices)
            ->map(function ($service) {
                return [
                    'name' => $service->service->name,
                    'duration' => $service->service->duration
                ];
            })->toArray();

        $this->totalDuration = array_sum(array_column($this->selectedServicesDetails, 'duration'));

        $this->step = 3;
    }

    public function findAvailableSlots(): void
    {
        if (empty($this->selectedDate)) {
            $this->error(__('client.error_date_required'));
            return;
        }

        try {
            $format_range = Peren::formatRangeDate($this->selectedDate);

            $info = [
                'branch_id' => auth()->user()->branch_id,
                'search_date_first' => $format_range['first_date'],
                'search_date_last' => $format_range['last_date'],
                'category_id' => $this->selectedCategory,
                'duration' => $this->totalDuration,
                'type' => 'range',
            ];

            $available_appointments_range = CheckAvailableAppointments::run($info);
            $this->formatAvailableSlots($available_appointments_range);
            $this->step = 4;
        } catch (\Throwable $e) {
            $this->error(__('client.error_try_again'));
        }
    }

    private function formatAvailableSlots($slots): void
    {
        $toSelect = [];
        foreach ($slots as $date => $times) {
            foreach ($times as $room) {
                $title = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y') . ' - ' . $room['name'];
                foreach ($room['gaps'] as $time) {
                    $toSelect[$title][] = [
                        'id' => Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y') . '||' . $room['name'] . '||' . $time,
                        'name' => $time,
                    ];
                }
            }
        }
        $this->available_appointments_range = $toSelect;
    }

    public function render()
    {
        return view('livewire.client.profil.create-appointment-page');
    }
}
