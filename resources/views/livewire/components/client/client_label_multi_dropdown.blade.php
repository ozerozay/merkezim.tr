<?php

use App\Actions\Label\GetActiveLabels;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    #[Modelable]
    public $client_labels;

    public $client_id;

    public Collection $labels;

    public function mount(): void
    {
        $this->labels = collect();
        $this->getLabels($this->client_id);
    }

    #[On('reload-labels')]
    public function reload_labels($client)
    {

        $this->getLabels($client);
    }

    public function getLabels($client, $status = null)
    {
        $this->labels = GetActiveLabels::run();
    }
};

?>

<div>
    <x-choices-offline
        wire:model="client_labels"
        :options="$labels"
        option-label="name"
        label="Etiketler"
        icon="o-magnifying-glass"
        searchable/>
</div>
