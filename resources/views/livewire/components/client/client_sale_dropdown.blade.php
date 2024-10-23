<?php

use App\Actions\Client\GetClientActiveSale;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $sale_id;

    public $client_id;

    public $status = null;

    public Collection $sales;

    public $label = 'Aktif Satışları';

    public function mount()
    {
        $this->getsales($this->client_id);
    }

    #[On('reload-sales')]
    public function reload_sales($client)
    {
        $this->getSales($client);
    }

    public function getSales($client, $status = null)
    {
        $this->sale_id = null;
        $this->sales = GetClientActiveSale::run($client, $status);
    }
};

?>

<div>
<x-choices-offline
    wire:model="sale_id"
    :options="$sales"
    option-sub-label="date"
    option-label="unique_id"
    :label="$label"
    icon="o-magnifying-glass"
    no-result-text="Aktif satışı bulunmuyor."
    single
    searchable />
</div>