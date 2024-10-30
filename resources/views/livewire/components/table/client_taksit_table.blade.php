<?php

use App\Actions\Client\GetClientTaksits;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends \Livewire\Volt\Component {

    use Toast, WithPagination;

    public ?LengthAwarePaginator $dataTable;

    public ?int $client = null;

    public array $sortBy = ['column' => 'date', 'direction' => 'asc'];

    public function mount(): void
    {
        $this->init();
    }

    public function init(): void
    {
        if ($this->client) {
            $this->dataTable = GetClientTaksits::run($this->client, true, $this->sortBy);
        }
    }

    public function headers(): array
    {
        return [
            ['key' => 'date', 'label' => 'Tarih', 'sortBy' => 'date'],
            ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'],
            ['key' => 'sale.sale_no', 'label' => 'Satış', 'sortBy' => 'sale_id'],
            ['key' => 'remaining', 'label' => 'Kalan', 'sortBy' => 'remaining'],
            ['key' => 'total', 'label' => 'Toplam', 'sortBy' => 'total'],
        ];
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers()
        ];
    }
};

?>
<div>
    <x-table :headers="$headers" :rows="$dataTable" :sort-by="$sortBy" striped="true" with-pagination="true">
        <x-slot:empty>
            <x-icon name="o-cube" label="Taksit bulunmuyor."/>
        </x-slot:empty>
        @scope('cell_sale.sale_no', $taksit)
        <p>{{ $taksit->sale_no }}</p>
        <p>{{ $taksit->unique_id }}</p>
        @endscope
        @scope('cell_status', $taksit)
        <x-badge :value="$taksit->status->label()" class="badge-{{ $taksit->status->color() }}"/>
        @endscope
        @scope('cell_total', $taksit)
        @price($taksit->total)
        @endscope
        @scope('cell_remaining', $taksit)
        @price($taksit->remaining)
        @endscope
    </x-table>
</div>
