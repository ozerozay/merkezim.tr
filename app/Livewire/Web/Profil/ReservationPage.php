<?php

namespace App\Livewire\Web\Profil;

use App\Models\Branch;
use App\Models\Service;
use App\Models\WebForm;
use App\Traits\WebSettingsHandler;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Mary\Traits\Toast;
use Carbon\Carbon;

#[Layout('components.layouts.client')]
#[Lazy()]
class ReservationPage extends Component
{
    use Toast, WebSettingsHandler;

    public $selectedServices = [];
    public $selectedDate;
    public $note;
    public $preferredTime;
    public $phone;
    public $selectedBranch;

    public $timePreferences = [
        ['id' => 'morning', 'name' => 'Öğleden Önce (09:00 - 13:00)'],
        ['id' => 'afternoon', 'name' => 'Öğleden Sonra (13:00 - Kapanış)']
    ];

    public function mount(): void
    {
        $this->selectedDate = Carbon::tomorrow()->format('Y-m-d');

        if (!auth()->check()) {
            // İlk aktif şubeyi seç
            $this->selectedBranch = Branch::where('active', true)->first()->id;
        }
    }

    public function createReservationRequest(): void
    {
        $rules = [
            'selectedServices' => 'required|array|min:1',
            'selectedDate' => 'required|date|after:today',
            'preferredTime' => 'required',
            'note' => 'nullable|string|max:500',
        ];

        if (!auth()->check()) {
            $rules['phone'] = 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10';
            $rules['selectedBranch'] = 'required|exists:branches,id';
        }

        $this->validate($rules, [
            'selectedServices.required' => __('client.error_service_required'),
            'selectedServices.min' => __('client.error_service_required'),
            'selectedDate.required' => __('client.error_date_required'),
            'selectedDate.after' => __('client.error_date_must_be_future'),
            'preferredTime.required' => __('client.error_time_required'),
            'phone.required' => __('client.error_phone_required'),
            'phone.regex' => __('client.error_phone_invalid'),
            'selectedBranch.required' => __('client.error_branch_required'),
        ]);

        try {
            WebForm::create([
                'branch_id' => $this->getBranchId(),
                'client_id' => auth()->check() ? auth()->id() : null,
                'type' => 'reservation_request',
                'data' => [
                    'services' => $this->selectedServices,
                    'date' => $this->selectedDate,
                    'time_preference' => $this->preferredTime,
                    'phone' => $this->phone ?? null,
                    'branch_id' => $this->selectedBranch
                ],
                'note' => $this->note,
                'status' => 'pending'
            ]);

            $this->success(__('client.reservation_request_success'));
            $this->reset(['selectedServices', 'note', 'preferredTime', 'phone']);
        } catch (\Exception $e) {
            $this->error(__('client.reservation_request_error'));
        }
    }

    private function getBranchId()
    {
        if (auth()->check()) {
            return auth()->user()->branch_id;
        }
        return $this->selectedBranch;
    }

    public function render()
    {
        $services = Service::where('active', true)
            ->where('is_visible', true)
            ->whereHas('category.branches', function ($query) {
                $query->where('branches.id', $this->getBranchId());
            })
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->duration . ' dk',
                ];
            });

        $branches = [];
        $showBranchSelect = false;

        if (!auth()->check()) {
            $branches = Branch::where('active', true)->get()
                ->map(function ($branch) {
                    return [
                        'id' => $branch->id,
                        'name' => $branch->name
                    ];
                });
            $showBranchSelect = $branches->count() > 1;
        }

        return view('livewire.client.profil.reservation-page', [
            'services' => $services,
            'showPhoneInput' => !auth()->check(),
            'branches' => $branches,
            'showBranchSelect' => $showBranchSelect
        ]);
    }
}
