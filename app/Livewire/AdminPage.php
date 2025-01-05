<?php

namespace App\Livewire;

use App\Models\AdminHomeWidget;
use App\Enum\AdminHomeWidgetType;
use Livewire\Component;

class AdminPage extends Component
{
    public $completedSteps = []; // Tamamlanan adımların dizisi
    public $userWidgets = [];    // Kullanıcının widget'ları
    public $selectedDateRange = 'today';
    public $dateRangeOptions = [
        'today' => 'Bugün',
        'yesterday' => 'Dün',
        'this_week' => 'Bu Hafta',
        'last_week' => 'Geçen Hafta',
        'this_month' => 'Bu Ay',
        'last_month' => 'Geçen Ay',
        'this_year' => 'Bu Yıl',
        'last_year' => 'Geçen Yıl'
    ];

    public function mount()
    {
        $this->loadUserWidgets();
    }

    public function createStep($step)
    {
        // Adımı tamamlanmış olarak işaretle
        if (!in_array($step, $this->completedSteps)) {
            $this->completedSteps[] = $step;
        }
    }

    public function loadSampleData()
    {
        // Tüm adımları tamamlanmış olarak işaretle
        $this->completedSteps = [1, 2, 3, 4, 5, 6];
    }

    public function loadUserWidgets()
    {
        $this->userWidgets = AdminHomeWidget::where('user_id', auth()->id())
            ->orderBy('order')
            ->get()
            ->map(function ($widget) {
                $type = AdminHomeWidgetType::from($widget->type);
                $icon = match ($type) {
                    AdminHomeWidgetType::sale => '💰',
                    AdminHomeWidgetType::appointment => '📅',
                    AdminHomeWidgetType::last_transactions => '🏦',
                    AdminHomeWidgetType::agenda => '📋',
                    default => '📊'
                };
                $bgColor = match ($type) {
                    AdminHomeWidgetType::sale => 'bg-primary/10',
                    AdminHomeWidgetType::appointment => 'bg-secondary/10',
                    AdminHomeWidgetType::last_transactions => 'bg-accent/10',
                    AdminHomeWidgetType::agenda => 'bg-info/10',
                    default => 'bg-neutral/10'
                };

                return [
                    'id' => $widget->id,
                    'type' => $widget->type,
                    'label' => $type->label(),
                    'icon' => $icon,
                    'bgColor' => $bgColor,
                    'visible' => $widget->visible,
                ];
            })
            ->toArray();
    }

    public function toggleWidget($widgetId)
    {
        $widget = AdminHomeWidget::where('user_id', auth()->id())
            ->where('id', $widgetId)
            ->first();

        if ($widget) {
            $widget->visible = !$widget->visible;
            $widget->save();
            $this->loadUserWidgets();
        }
    }

    public function updateWidgetOrder($items)
    {
        foreach ($items as $item) {
            AdminHomeWidget::where('id', $item['value'])
                ->where('user_id', auth()->id())
                ->update(['order' => $item['order']]);
        }

        $this->loadUserWidgets();
    }

    public function updatedSelectedDateRange($value)
    {
        $this->dispatch('dateRangeChanged', range: $value);
    }

    public function render()
    {
        return view('livewire.spotlight.admin.admin-page');
    }
}
