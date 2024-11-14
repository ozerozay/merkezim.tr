<?php

use Carbon\Carbon;

new
#[\Livewire\Attributes\Title('Ödeme Takip Oluştur')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    public $client = null;

    public $config2;

    public $date = null;

    public $name;

    public $price = 0;

    public $frequency = 'tek';

    public $installment = 1;

    public $branch;

    public $message;

    public array $frequencyList = [
        [
            'id' => 'tek',
            'name' => 'Tek',
        ],
        [
            'id' => 'gun',
            'name' => 'Günlük',
        ],
        [
            'id' => 'hafta',
            'name' => 'Haftalık',
        ],
        [
            'id' => 'ay',
            'name' => 'Aylık',
        ],
    ];

    public function mount(): void
    {
        $this->config2 = \App\Peren::dateConfig(enableTime: false);
        $this->date = date('Y-m-d');
    }

    public function save(): void
    {
        $validator = Validator::make(
            [
                'branch_id' => $this->branch,
                'frequency' => $this->frequency,
                'name' => $this->name,
                'date' => $this->date,
                'date_create' => $this->date,
                'price' => $this->price,
                'type' => 'payment',
                'user_id' => auth()->user()->id,
                'installment' => $this->installment,
                'message' => $this->message,
            ],
            [
                'branch_id' => 'required',
                'frequency' => 'required',
                'name' => 'required',
                'date' => 'required',
                'date_create' => 'required',
                'type' => 'required',
                'price' => 'nullable|decimal:0,2',
                'user_id' => 'required|exists:users,id',
                'installment' => 'nullable',
                'message' => 'required'
            ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        \App\Actions\Agenda\CreatePaymentTrackingAction::run($validator->validated());

        $this->success('Oluşturuldu.');
        $this->reset('branch', 'name', 'frequency', 'date', 'price');

    }
};

?>
<div>
    <x-card title="Ödeme Takip Oluştur" progress-indicator separator>
        <x-form wire:submit="save">

            <x-radio
                label="Sıklık" :options="$frequencyList" wire:model.live="frequency"/>
            <livewire:components.form.branch_dropdown wire:model="branch"/>
            <x-input label="Ödeme Adı" wire:model="name"/>
            <x-datepicker label="Başlangıç Tarihi" wire:model="date" icon="o-calendar" :config="$config2"/>
            <x-input label="Tutar - Boş bırakabilirsiniz. Fatura vb ödemeler için" wire:model="price" suffix="₺" money/>
            @if ($frequency != 'tek')
                <livewire:components.form.number_dropdown wire:model="installment" label="Taksit"/>
            @endif
            <x-input label="Açıklama" wire:model="message"/>
            <x:slot:actions>
                <x-button label="Gönder" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary"/>
            </x:slot:actions>
        </x-form>
    </x-card>
</div>
