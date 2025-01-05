<?php

namespace App\Livewire\Admin\Widgets;

use App\Models\Branch;
use App\AppointmentStatus;
use App\Traits\AdminWidgetTrait;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy()]
class AppointmentWidget extends Component
{
    use AdminWidgetTrait;

    public $selectedBranchesData = [];
    public $selectedDateRange = 'today';

    public function loadData(): void
    {
        $dateRange = $this->getDateRange();

        $this->selectedBranchesData = Branch::whereIn('id', auth()->user()->staff_branches)
            ->where('active', true)
            ->whereIn('id', $this->selectedBranches)
            ->withCount([
                'appointments as total_appointments' => function ($query) use ($dateRange) {
                    $query->where('date', '>=', $dateRange['start'])
                        ->where('date', '<=', $dateRange['end']);
                },
                'appointments as completed_count' => function ($query) use ($dateRange) {
                    $query->where('date', '>=', $dateRange['start'])
                        ->where('date', '<=', $dateRange['end'])
                        ->where('status', AppointmentStatus::finish);
                },
                'appointments as cancelled_count' => function ($query) use ($dateRange) {
                    $query->where('date', '>=', $dateRange['start'])
                        ->where('date', '<=', $dateRange['end'])
                        ->where('status', AppointmentStatus::deactive()->toArray());
                },
                'appointments as pending_count' => function ($query) use ($dateRange) {
                    $query->where('date', '>=', $dateRange['start'])
                        ->where('date', '<=', $dateRange['end'])
                        ->whereIn('status', AppointmentStatus::active()->toArray());
                }
            ])
            ->get()
            ->map(function ($branch) {
                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'total_appointments' => $branch->total_appointments ?? 0,
                    'completed_count' => $branch->completed_count ?? 0,
                    'cancelled_count' => $branch->cancelled_count ?? 0,
                    'pending_count' => $branch->pending_count ?? 0,
                ];
            })->toArray();
    }

    public function render()
    {
        return view('livewire.spotlight.admin.widgets.appointment-widget');
    }
}
