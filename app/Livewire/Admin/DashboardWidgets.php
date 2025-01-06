<?php

namespace App\Livewire\Admin;

use App\Models\AdminHomeWidget;
use Livewire\Component;
use Illuminate\Support\Str;

class DashboardWidgets extends Component
{
    public $widgets;
    public $loadedWidgets = [];
    public $uniqueId;

    public function mount(): void
    {
        $this->uniqueId = Str::random(10);
        $this->widgets = auth()->user()->adminHomeWidgets()->orderBy('order')->get();
        foreach ($this->widgets as $widget) {
            $this->loadedWidgets[$widget->id] = false;
        }
    }

    public function loadWidget($widgetId): void
    {
        if (!isset($this->loadedWidgets[$widgetId])) {
            $this->loadedWidgets[$widgetId] = false;
        }
        $this->loadedWidgets[$widgetId] = true;
    }

    public function render()
    {
        return view('livewire.spotlight.admin.dashboard-widgets', [
            'widgets' => $this->widgets,
            'loadedWidgets' => $this->loadedWidgets,
            'uniqueId' => $this->uniqueId,
        ]);
    }
}
