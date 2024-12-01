<?php

namespace App\Livewire\Actions\Kasa;

use App\Models\Kasa;
use App\Models\Transaction;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class KasaProcesses extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public int|Kasa $kasa;

    public function mount(Kasa $kasa): void
    {
        $this->kasa = $kasa->load('branch:id,name');
    }

    public $listeners = [
        'kasa-process-update' => '$refresh',
    ];

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function transactions()
    {
        return Transaction::query()
            ->where('kasa_id', $this->kasa->id)
            ->where('date', date('Y-m-d'))
            ->orderBy('price', 'asc')
            ->orderBy('created_at')
            ->with('kasa:id,name', 'user:id,name', 'masraf:id,name', 'client:id,name')
            ->paginate(10);
    }

    public function totalPositiveTransactions()
    {
        return Transaction::query()
            ->where('kasa_id', $this->kasa->id)
            ->where('date', date('Y-m-d'))
            ->where('price', '>', 0)
            ->sum('price');
    }

    public function totalNegativeTransactions()
    {
        return Transaction::query()
            ->where('kasa_id', $this->kasa->id)
            ->where('date', date('Y-m-d'))
            ->where('price', '<', 0)
            ->sum('price');
    }

    public function render()
    {
        return view('livewire.spotlight.actions.kasa.kasa-processes', [
            'transactions' => $this->transactions(),
            'gelir' => $this->totalPositiveTransactions(),
            'gider' => $this->totalNegativeTransactions(),
        ]);
    }
}
