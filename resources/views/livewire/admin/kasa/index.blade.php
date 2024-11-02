<?php

use App\Models\Kasa;
use App\Traits\LiveHelper;
use Carbon\Carbon;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[\Livewire\Attributes\Title('Kasa')]
class extends Component {
    use Toast;

    public $config2 = ['altFormat' => 'd/m/Y', 'locale' => 'tr', 'mode' => 'range'];

    public $date = null;

    public $staff_branches;

    public $branches;

    public $branch_sql;

    public $tutar;

    public $transactions;

    public $headers;

    public $first_date_string;

    public $last_date_string;

    public function mount()
    {
        //LiveHelper::class;
        $this->date = Carbon::now()->format('Y-m-d 00:00') . ' - ' . Carbon::now()->format('Y-m-d 00:00');
        $this->staff_branches = auth()->user()->staff_branch;
        foreach ($this->staff_branches as $branch) {
            $this->branches[$branch->id] =
                ['checked' => true];
        }
        $this->branch_sql = auth()->user()->staff_branches;
        $this->getTransactions();
        $this->getHeaders();
    }

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function getHeaders()
    {
        $this->headers = [
            ['key' => 'kasa_adi', 'label' => 'Kasa', 'sortable' => false],
            ['key' => 'kasa_id', 'label' => 'Kasa', 'sortable' => false, 'hidden' => true],
            ['key' => 'devir', 'label' => 'Devir', 'sortable' => false],
            ['key' => 'tahsilat', 'label' => 'Tahsilat', 'sortable' => false],
            ['key' => 'odenen', 'label' => 'Ödeme', 'sortable' => false],
            ['key' => 'bakiye', 'label' => 'Bakiye', 'sortable' => false],

        ];
    }

    public function getTransactions()
    {
        try {

            $first_date = null;
            $last_date = null;
            $split_date = preg_split('/\s-\s/', $this->date);
            if (count($split_date) > 1) {
                $first_date = Carbon::createFromFormat('Y-m-d H:i', $split_date[0])->format('Y-m-d');
                $last_date = Carbon::createFromFormat('Y-m-d H:i', $split_date[1])->format('Y-m-d');
            } else {
                $first_date = $last_date = Carbon::createFromFormat('Y-m-d H:i', $split_date[0])->format('Y-m-d');
            }

            $this->first_date_string = $first_date;
            $this->last_date_string = $last_date;

            $transactions_sql = Kasa::query()
                ->whereIn('kasas.branch_id', $this->branch_sql)
                ->where('kasas.active', true)
                ->leftJoin('transactions', 'kasas.id', '=', 'transactions.kasa_id')
                ->leftJoin('branches', 'branches.id', '=', 'kasas.branch_id')
                ->selectRaw('branches.name as branch_name,branches.id as branch_id,kasas.name as kasa_adi, kasas.id as id,
    SUM(CASE WHEN transactions.date < ? THEN transactions.price ELSE 0 END) as devir,
    SUM(CASE WHEN (transactions.date <= DATE(?) and transactions.date >= DATE(?)) AND transactions.price < 0 THEN transactions.price ELSE 0 END) as odenen,
    SUM(CASE WHEN (transactions.date <= ? and transactions.date >= ?) AND transactions.price > 0 THEN transactions.price ELSE 0 END) as tahsilat
', [$first_date, $last_date, $first_date, $last_date, $first_date])
                ->groupBy('kasas.id', 'kasas.name')
                ->get();
            $this->transactions = collect($transactions_sql)->groupBy('branch_id')->toArray();

            //dump(collect($this->transactions));
        } catch (\Throwable $e) {
            $this->error('Tekrar deneyin.' . $e->getMessage());
        }
    }

    public function with()
    {
        return [
            'headers' => $this->headers,
            'transactions' => $this->transactions,
        ];
    }

    public function filter()
    {
        $branch_ids = [];

        foreach ($this->branches as $key => $branch) {
            if ($branch['checked'] == true) {
                $branch_ids[] = $key;
            }
        }

        if (count($branch_ids) < 1) {
            $this->error('Şube seçmelisiniz.');

            return;
        }

        $this->branch_sql = $branch_ids;

        $this->getTransactions();
    }

    public array $expanded = [];
};

?>
<div>
    <x-header title="Kasa" separator progress-indicator>
        <x-slot:actions>
            <x-dropdown label="Tarih">
                <x-slot:trigger>
                    <x-button icon="tabler.filter" class="btn-outline" label="Filtrele" responsive/>
                </x-slot:trigger>
                <x-form wire:submit="filter">
                    <x-menu-item @click.stop="">
                        <x-datepicker label="Tarih" wire:model="date" icon="o-calendar" :config="$config2" inline/>
                    </x-menu-item>
                    <x-menu-separator/>
                    @foreach(auth()->user()->staff_branch as $branch)
                        <x-menu-item @click.stop="">
                            <x-checkbox wire:model="branches.{{ $branch->id }}.checked" label="{{ $branch->name }}"/>
                        </x-menu-item>
                    @endforeach
                    <x:slot:actions>
                        <x-button class="btn-outline" type="submit" label="Gönder"/>
                    </x:slot:actions>
                </x-form>
            </x-dropdown>
        </x-slot:actions>
    </x-header>
    @php
        $collected_transaction = collect($transactions)->flatten(1);
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-5">
        <x-stat title="Devir" value="{{ LiveHelper::price_text($collected_transaction->sum('devir')) }}"
                icon="o-credit-card"/>
        <x-stat title="Tahsilat" value="{{ LiveHelper::price_text($collected_transaction->sum('tahsilat')) }}"
                icon="o-credit-card"/>
        <x-stat title="Ödeme" value="{{ LiveHelper::price_text($collected_transaction->sum('odenen')) }}"
                icon="o-credit-card"/>
        <x-stat title="Bakiye" value="{{ LiveHelper::price_text($collected_transaction->sum('bakiye')) }}"
                icon="o-credit-card"/>
    </div>
    @foreach($transactions as $sube)
        @php
            $collected = collect($sube);
        @endphp
        <x-card :title="$sube[0]['branch_name']" class="mb-2">
            <div class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <x-stat title="Devir" value="{{ LiveHelper::price_text($collected->sum('devir')) }}"
                        icon="o-credit-card"/>
                <x-stat title="Tahsilat" value="{{ LiveHelper::price_text($collected->sum('tahsilat')) }}"
                        icon="o-credit-card"/>
                <x-stat title="Ödeme" value="{{ LiveHelper::price_text($collected->sum('odenen')) }}"
                        icon="o-credit-card"/>
                <x-stat title="Bakiye" value="{{ LiveHelper::price_text($collected->sum('bakiye')) }}"
                        icon="o-credit-card"/>
            </div>
            <x-hr/>
            <x-table :headers="$headers" :rows="$sube" :sort-by="$sortBy"
                     link="kasa/detail?kasa={id}&start={{ $first_date_string  }}&end={{ $last_date_string  }}"
                     striped>
                @scope('cell_bakiye', $tra)
                {{ LiveHelper::price_text($tra['devir'] + $tra['tahsilat'] + $tra['odenen']) }}
                @endscope
                @scope('cell_devir', $tra)
                {{ LiveHelper::price_text($tra['devir']) }}
                @endscope
                @scope('cell_tahsilat', $transaction)
                {{ LiveHelper::price_text($transaction['tahsilat']) }}
                @endscope
                @scope('cell_odenen', $transaction)
                {{ LiveHelper::price_text($transaction['odenen']) }}
                @endscope
            </x-table>
        </x-card>
    @endforeach
</div>
