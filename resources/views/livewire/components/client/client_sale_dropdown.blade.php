<?php

use App\Models\Sale;
use App\SaleStatus;
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
        $this->sales = Sale::query()
            ->select(['id', 'unique_id', 'client_id', 'status', 'date'])
            ->where('client_id', $client)
            ->where('status', $status ?? SaleStatus::success)
            ->get();
    }
};

?>

<div>
<x-choices-offline
    wire:model="sale_id"
    :options="$sales"
    option-sub-label="date"
    option-label="unique_id"
    label="Aktif Satışları"
    icon="o-magnifying-glass"
    single
    searchable />
</div>