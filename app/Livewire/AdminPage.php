<?php

namespace App\Livewire;

use Livewire\Component;

class AdminPage extends Component
{
    public $completedSteps = []; // Tamamlanan adımların dizisi

    public function createStep($step)
    {
        // Adımı tamamlanmış olarak işaretle
        if (! in_array($step, $this->completedSteps)) {
            $this->completedSteps[] = $step;
        }
    }

    public function loadSampleData()
    {
        // Tüm adımları tamamlanmış olarak işaretle
        $this->completedSteps = [1, 2, 3, 4, 5, 6];
    }

    public function render()
    {
        return view('livewire.admin-page');
    }
}
