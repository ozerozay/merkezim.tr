<?php

namespace App\Livewire\Admin\Widgets;

use App\Models\Agenda;
use App\AgendaType;
use App\Traits\AdminWidgetTrait;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy()]
class AgendaWidget extends Component
{
    use AdminWidgetTrait;

    public $agendaItems = [];
    public $selectedDateRange = 'today';

    public function loadData(): void
    {
        $dateRange = $this->getDateRange();

        $query = Agenda::query()
            ->with('user:id,name')
            ->when(!auth()->user()->hasRole('admin'), function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->whereIn('branch_id', $this->selectedBranches)
            ->where('date', '>=', $dateRange['start'])
            ->where('date', '<=', $dateRange['end'])
            ->orderBy('date', 'desc');

        $this->agendaItems = $query->get()
            ->map(function ($item) {
                $type = $item->type;
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'message' => $item->message,
                    'date' => $item->date,
                    'type' => $type->value,
                    'type_label' => $type->label(),
                    'type_color' => $type->color(),
                    'price' => $type->name === AgendaType::payment->name ? $item->price : null,
                    'user_id' => $item->user_id,
                    'user_name' => $item->user->name,
                    'is_owner' => $item->user_id === auth()->id(),
                ];
            })->toArray();
    }

    public function render()
    {
        return view('livewire.spotlight.admin.widgets.agenda-widget');
    }
}
