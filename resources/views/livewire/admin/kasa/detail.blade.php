<?php

use App\Models\Kasa;
use App\Models\Transaction;
use Carbon\Carbon;
use Mary\Traits\Toast;
use Livewire\Attributes\Url;

new class extends \Livewire\Volt\Component {

    use Toast;

    #[Url(as: 'kasa')]
    public ?int $kasa_id = null;

    public ?Kasa $kasa;

    #[Url(as: 'start')]
    public ?string $date_start = null;

    #[Url(as: 'end')]
    public ?string $date_end = null;

    public $config2 = ['altFormat' => 'd/m/Y', 'locale' => 'tr', 'mode' => 'range'];

    #[\Livewire\Attributes\Rule('required')]
    public $date = null;

    public $transactions;

    public function mount()
    {
        $this->init();
    }

    public function init()
    {
        $this->kasa = Kasa::where('id', $this->kasa_id)->first();
        if (!$this->kasa) {
            $this->error('Kasa bulunamadı.');

            return $this->redirect(route('admin.kasa'));
        }
        if (!in_array($this->kasa->branch_id, auth()->user()->staff_branches)) {
            $this->error('Kasa bulunamadı.');

            return $this->redirect(route('admin.kasa'));
        }

        $this->transactions = Transaction::where('kasa_id', $this->kasa->id)
            ->whereDate('date', '>=', $this->date_start)
            ->whereDate('date', '<=', $this->date_end)
            ->with('user:id,name')
            ->get();
    }

    public function changeDate(): void
    {
        $this->validate();
        $this->checkDate($this->date);
        $this->init();
    }

    public function checkDate($date): void
    {
        $first_date = null;
        $last_date = null;
        $split_date = preg_split('/\s-\s/', $this->date);
        if (count($split_date) > 1) {
            $this->date_start = Carbon::createFromFormat('Y-m-d H:i', $split_date[0])->format('Y-m-d');
            $this->date_end = Carbon::createFromFormat('Y-m-d H:i', $split_date[1])->format('Y-m-d');
        } else {
            $this->date_start = $this->date_end = Carbon::createFromFormat('Y-m-d H:i', $split_date[0])->format('Y-m-d');
        }
    }

};

?>
<div>
    <x-header title="{{ $kasa->name  }}" separator>
        <x-slot:actions>
            <x-dropdown label="Tarih">
                <x-slot:trigger>
                    <x-button icon="tabler.filter" class="btn-outline" label="Filtrele" responsive/>
                </x-slot:trigger>
                <x-form wire:submit="changeDate">
                    <x-menu-item @click.stop="">
                        <x-datepicker label="Tarih" wire:model="date" icon="o-calendar" :config="$config2" inline/>
                    </x-menu-item>
                    <x:slot:actions>
                        <x-button class="btn-outline" type="submit" label="Gönder"/>
                    </x:slot:actions>
                </x-form>
            </x-dropdown>
        </x-slot:actions>
    </x-header>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach($transactions as $transaction)
            <x-card title="{{ $transaction->price }}" separator class="mb-2">
                <x-slot:title>
                    @if ($transaction->price < 0)
                        <p class="text-red-500">@price($transaction->price)</p>
                    @else
                        <p class="text-green-500">@price($transaction->price)</p>
                    @endif
                </x-slot:title>
                <x-list-item :item="$transaction">
                    <x-slot:value>
                        Tarih
                    </x-slot:value>
                    <x-slot:actions>
                        {{$transaction->date_human}}
                    </x-slot:actions>
                </x-list-item>
                <x-list-item :item="$transaction">
                    <x-slot:value>
                        Personel
                    </x-slot:value>
                    <x-slot:actions>
                        {{$transaction->user->name ?? ''}}
                    </x-slot:actions>
                </x-list-item>
                <x-list-item :item="$transaction">
                    <x-slot:value>
                        Açıklama
                    </x-slot:value>
                    <x-slot:actions>
                        {{$transaction->message}}
                    </x-slot:actions>
                </x-list-item>
                <x-list-item :item="$transaction">
                    <x-slot:value>
                        Çeşit
                    </x-slot:value>
                    <x-slot:actions>
                        {{$transaction->type->label()}}
                    </x-slot:actions>
                </x-list-item>
            </x-card>
        @endforeach
    </div>
</div>
