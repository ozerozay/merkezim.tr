<?php

use App\Actions\Kasa\CreatePaymentAction;
use App\Actions\User\CheckUserInstantApprove;
use App\Actions\User\CreateApproveRequestAction;
use App\ApproveTypes;
use App\Models\Kasa;
use App\Models\Masraf;
use App\Models\User;
use App\Peren;
use App\Traits\LiveHelper;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Ödeme Yap')]
class extends Component {
    use Toast;

    public ?Collection $payments;

    public function mount(): void
    {
        //LiveHelper::class
        $this->payments = collect();
    }

    #[On('payment-staff-payment-added')]
    public function paymentStaffAdded($payment): void
    {
        $lastId = $this->payments->last() != null ? $this->payments->last()['id'] + 1 : 1;
        $payment['id'] = $lastId;
        $payment['kasa_name'] = Kasa::select('id', 'name')->where('id', $payment['kasa_id'])->first()->name ?? '';
        if ($payment['type'] == 'Personel') {
            $payment['staff_name'] = User::select('id', 'name')->where('id', $payment['staff_id'])->first()->name ?? '';
        }
        if ($payment['type'] == 'Danışan') {
            $payment['staff_name'] = User::select('id', 'name')->where('id', $payment['client_id'])->first()->name ?? '';
        }
        $payment['masraf_name'] = Masraf::select('id', 'name')->where('id', $payment['masraf_id'])->first()->name ?? '';

        $payment['user_id'] = auth()->user()->id;
        $payment['date'] = \Carbon\Carbon::createFromFormat('Y-m-d', $payment['date'])->format('d/m/Y');
        $this->payments->push($payment);
    }

    public function deleteItem($id): void
    {
        $this->payments = $this->payments->reject(function ($q) use ($id) {
            return $q['id'] == $id;
        });
    }

    public function save(): void
    {
        if ($this->payments->isEmpty()) {
            $this->error('Ödeme bilgisi girmelisiniz.');

            return;
        }

        if (CheckUserInstantApprove::run(auth()->user()->id)) {
            CreatePaymentAction::run($this->payments);
            $this->success('Ödemeler yapıldı.');
        } else {
            CreateApproveRequestAction::run($this->payments, auth()->user->id, ApproveTypes::payment, '');
            $this->success(Peren::$approve_request_ok);
        }

        $this->payments = collect();

    }
};

?>
<div>
    <x-header title="Ödeme Yap - Al" progress-indicator separator>
        <x:slot:actions>
            <livewire:components.card.make_payment.card_payment_add_payment/>
        </x:slot:actions>
    </x-header>
    <x-card title="İşlemler" separator progress-indicator>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ($payments as $payment)
                <x-list-item :item="$payment">
                    <x-slot:value>
                        {{ $payment['staff_name'] ?? $payment['masraf_name'] }} - {{ $payment['type'] }} <p>{{ LiveHelper::price_text($payment['price'])
                    }}</p>
                    </x-slot:value>
                    <x-slot:sub-value>
                        {{ $payment['date'] }} - {{ $payment['kasa_name'] }} - {{ $payment['masraf_name'] }}
                        <p>{{ $payment['message'] }}</p>
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <x-button icon="o-trash" class="text-red-500" wire:click="deleteItem({{ $payment['id'] }})"
                                  spinner="save"/>
                    </x-slot:actions>
                </x-list-item>
            @endforeach
        </div>
        <x:slot:menu>
            <p class="text-end">Toplam: {{ LiveHelper::price_text($payments->sum('price')) }} </p>
        </x:slot:menu>
        <x:slot:actions>
            @if ($payments->count() > 0)
                <x-button label="Ödeme Yap" icon="o-credit-card" wire:click='save' spinner="save" class="btn-primary"/>
            @endif
        </x:slot:actions>
    </x-card>
</div>
