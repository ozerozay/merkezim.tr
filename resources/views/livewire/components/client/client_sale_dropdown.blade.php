<?php

use App\Actions\Client\GetClientActiveSale;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $sale_id = 0;

    public $client_id;

    public $status = null;

    public Collection $sales;

    public $label = 'Aktif Satışları';

    public function mount(): void
    {
        $this->getsales($this->client_id);
    }

    #[On('reload-sales')]
    public function reload_sales($client): void
    {
        $this->getSales($client);
    }

    public function getSales($client, $status = null): void
    {
        $sales = GetClientActiveSale::run($client, $status);
        $this->sales = $sales->isEmpty() ? collect([]) : $sales;
    }
};

?>
<div wire:key="csdd-{{Str::random(20)}}">
    <x-choices-offline
        wire:key="csd-{{Str::random(20)}}"
        wire:model="sale_id"
        :options="$sales"
        option-sub-label="date"
        option-label="unique_id"
        :label="$label"
        icon="o-magnifying-glass"
        no-result-text="Aktif satışı bulunmuyor."
        single
        searchable/>

</div>


