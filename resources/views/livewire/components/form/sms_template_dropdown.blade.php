<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $template_id = null;

    public $templates;

    public function mount()
    {
        $this->templates = App\Models\SMSTemplate::query()
            ->whereIn('branch_id', auth()->user()->staff_branches)
            ->where('active', true)
            ->get();
    }
};
?>

<div>
    <x-choices-offline label="Şablonlar" single wire:model="template_id" :options="$templates" />
</div>
