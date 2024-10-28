<?php

use App\Actions\Client\GetClientTaksits;
use App\Models\ClientTaksit;
use App\TaksitStatus;
use App\Traits\LiveHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast, WithPagination;

    public $client;

    public $client_taksits;

    public bool $taksit_edit = false;

    public ?ClientTaksit $selected_taksit;

    public $view = 'table';

    public array $sortBy = ['column' => 'date', 'direction' => 'asc'];

    public function headers()
    {
        //LiveHelper::class;

        return [
            ['key' => 'date', 'label' => 'Tarih', 'sortBy' => 'date'],
            ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'],
            ['key' => 'sale.unique_id', 'label' => 'Satış', 'sortBy' => 'sale_id'],
            ['key' => 'remaining', 'label' => 'Kalan', 'sortBy' => 'remaining'],
            ['key' => 'total', 'label' => 'Toplam', 'sortBy' => 'total'],
        ];
    }

    public function getTaksits(): LengthAwarePaginator
    {
        return GetClientTaksits::run($this->client, true, $this->sortBy);
    }

    public function with()
    {
        $taksits = $this->getTaksits();

        $late_payment = $taksits->where('status', TaksitStatus::late_payment)->sum('remaining');

        $remaining_payment = $taksits->where('status', TaksitStatus::success)->sum('remaining');

        $total_payment = $taksits->whereIn('status', [TaksitStatus::success, TaksitStatus::waiting, TaksitStatus::late_payment])->sum('total');

        return [
            'taksits' => $this->getTaksits(),
            'headers' => $this->headers(),
            'late_payment' => $late_payment,
            'remaining_payment' => $remaining_payment,
            'total_payment' => $total_payment,
        ];
    }

    public function placeholder()
    {
        return view('livewire.components.card.loading.loading');
    }

    public function changeView()
    {
        $this->view = $this->view == 'table' ? 'list' : 'table';
    }

    public array $expanded = [];
};
?>
<div>
    <div class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
        <x-stat title="Toplam Aktif" value="{{ LiveHelper::price_text($total_payment) }}" icon="o-credit-card" />
        <x-stat title="Kalan Ödeme" value="{{ LiveHelper::price_text($remaining_payment) }}" icon="o-credit-card" />
        <x-stat title="Gecikmiş Ödeme" value="{{ LiveHelper::price_text($late_payment) }}" class="text-red-500"
            icon="o-credit-card" />
    </div>
    <div class="flex justify-end mb-4 mt-5 gap-2">
        <p>Sıralama işlemlerini tablo görünümünden yapabilirsiniz.</p>
        <x-button wire:click="changeView" label="{{ $view == 'table' ? 'LİSTE' : 'TABLO' }}"
            icon="{{ $view == 'table' ? 'tabler.list' : 'tabler.table' }}" class="btn btn-sm btn-outline" />

    </div>
    @if ($view == 'table')
    <div>
        <x-card>
            <x-table :headers="$headers" :rows="$taksits" :sort-by="$sortBy" striped with-pagination>
                <x-slot:empty>
                    <x-icon name="o-cube" label="Taksit bulunmuyor." />
                </x-slot:empty>
                @scope('cell_sale.unique_id', $taksit)
                {{ $taksit->sale->unique_id ?? '-' }}
                @endscope
                @scope('cell_status', $taksit)
                <x-badge :value="$taksit->status->label()" class="badge-{{ $taksit->status->color() }}" />
                @endscope
                @scope('cell_total', $taksit)
                {{ LiveHelper::price_text($taksit->total) }}
                @endscope
                @scope('cell_remaining', $taksit)
                {{ LiveHelper::price_text($taksit->remaining) }}
                @endscope
            </x-table>
        </x-card>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @if ($taksits->count() == 0)
        <p class="text-center">Taksit bulunmuyor.</p>
        @endif
        @foreach ($taksits as $taksit)
        <x-card title="{{ $taksit->date_human_created }}" separator class="mb-2">
            <x-list-item :item="$taksit">
                <x-slot:value>
                    Durum
                </x-slot:value>
                <x-slot:actions>
                    <x-badge :value="$taksit->status->label()" class="badge-{{ $taksit->status->color() }}" />
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$taksit">
                <x-slot:value>
                    Satış
                </x-slot:value>
                <x-slot:actions>
                    {{ $taksit->sale->unique_id ?? 'YOK' }}
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$taksit">
                <x-slot:value>
                    Toplam
                </x-slot:value>
                <x-slot:actions>
                    {{ $taksit->total }}
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$taksit">
                <x-slot:value>
                    Kalan
                </x-slot:value>
                <x-slot:actions>
                    {{ $taksit->remaining }}
                </x-slot:actions>
            </x-list-item>
        </x-card>
        @endforeach
    </div>
    @endif
</div>