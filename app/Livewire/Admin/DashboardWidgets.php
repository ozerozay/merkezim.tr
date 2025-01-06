<?php

namespace App\Livewire\Admin;

use App\Models\AdminHomeWidget;
use Livewire\Component;

class DashboardWidgets extends Component
{
    public $widgets;
    public $loadedWidgets = [];
    protected $listeners = ['widgetLoaded'];

    public function mount(): void
    {
        $this->widgets = auth()->user()->adminHomeWidgets()->orderBy('order')->get();
        foreach ($this->widgets as $widget) {
            $this->loadedWidgets[$widget->id] = false;
        }
    }

    public function loadWidget($widgetId): void
    {
        $this->loadedWidgets[$widgetId] = true;
        $this->dispatch('widgetLoaded', widgetId: $widgetId);
    }

    public function widgetLoaded($widgetId): void
    {
        $this->loadedWidgets[$widgetId] = true;
    }

    public function toggleWidget($id): void
    {
        $widget = AdminHomeWidget::query()->find($id);
        if ($widget) {
            $widget->update(['visible' => ! $widget->visible]);
        }
        $this->mount();
    }

    public function updateOrder($order): void
    {
        foreach ($order as $index => $id) {
            AdminHomeWidget::query()->where('id', $id)->update(['order' => $index + 1]);
        }
        $this->mount();
    }

    public function render()
    {
        return view('livewire.spotlight.admin.dashboard-widgets');
    }
}
