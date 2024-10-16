<?php

use App\Models\Branch;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {

    #[Modelable]
    public $number;

    public $numbers;

    public $label = '';

    public bool $includeZero = false;

    public int $max = 100;
    
    public $suffix;

    public function mount()
    {
        $range = $this->includeZero ? range(0, $this->max) : range(1, $this->max);
        $numbers = [];
        foreach ($range as $r) {
            $numbers[] = [
                'id' => $r,
                'name' => $r . ' ' . $this->suffix
            ];
        }
        $this->numbers = $numbers;
    }
};
?>
<div>
<x-select :label="$label" icon="tabler.circle-number-0" :options="$numbers" wire:model="number" />
</div>
