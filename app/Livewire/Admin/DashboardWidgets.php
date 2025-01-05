<?php

namespace App\Livewire\Admin;

use App\Models\AdminHomeWidget;
use Livewire\Component;

class DashboardWidgets extends Component
{
    public $widgets;

    public function mount(): void
    {
        $this->widgets = auth()->user()->adminHomeWidgets()->orderBy('order')->get();
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
        return view('livewire.spotlight.admin.dashboard-widgets', [
            'widgets' => $this->widgets,
        ]);
    }
}
