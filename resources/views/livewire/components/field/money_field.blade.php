<?php

use Illuminate\Support\Number;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $money;

    public function boot()
    {
        $this->money = Number::currency((float)$this->money, in: 'TRY', locale: 'tr');
    }
};
?>
<div>
    {{ $money }}
</div>