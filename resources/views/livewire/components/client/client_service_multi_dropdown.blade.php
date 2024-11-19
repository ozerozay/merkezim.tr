<?php

use App\Actions\Client\GetClientActiveService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $service_ids;

    public $client_id;

    public Collection $services;

    public $category_id;

    public $label = 'Aktif Hizmetler';

    public function mount(): void
    {
        $this->getServices($this->client_id, null, $this->category_id);
    }

    #[On('reload-services')]
    public function reload_services($client, $service_ids = [], $yok = null): void
    {
        $this->getServices($client, $status = null, $this->category_id, $service_ids, $yok);

    }

    public function getServices($client, $status = null, $category_id = null, $selected = null, $yok = null): void
    {
        //$this->service_ids = $selected;
        //dump($this->service_ids);
        $this->services = GetClientActiveService::run($client, $status, $category_id, $yok);
    }
};

?>
<div>
    <x-choices-offline
        wire:model="service_ids"
        :options="$services"
        option-label="service.name"
        :label="$label"
        icon="o-magnifying-glass"
        no-result-text="Aktif hizmeti bulunmuyor."
        class="z-[999]"
        searchable>
        @scope('item', $service)
        <x-list-item :item="$service" sub-value="service.name">
            <x-slot:actions>
                <x-badge value="Kalan: {{ $service->remaining }} seans"/>
            </x-slot:actions>
        </x-list-item>
        @endscope
        @scope('selection', $service)
        {{ $service->service->duration }} dk - {{ $service->service->name }} ({{ $service->remaining }})
        @endscope
    </x-choices-offline>
</div>
