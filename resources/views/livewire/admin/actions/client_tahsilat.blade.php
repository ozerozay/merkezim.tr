<?php

use Livewire\Attributes\On;
use Livewire\Attributes\Url;

new
#[\Livewire\Attributes\Title('Tahsilat')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    #[Url]
    public $client = null;

    public $client_model = null;

    public $kasa_id;

    public $price;

    public $date;
    public $date_config;

    public function mount(): void
    {
        $this->date = date('Y-m-d');
        $this->date_config = \App\Peren::dateConfig();
        $this->client_model = \App\Actions\Client\GetClientByUniqueID::run(null, $this->client);

        if ($this->client_model) {
            $this->client = $this->client_model->id;
        }
    }

    #[On('client-selected')]
    public function clientSelected($client = null)
    {
        $this->client_model = \App\Actions\Client\GetClientByUniqueID::run(null, $client);

        if ($this->client_model) {
            $this->client = $this->client_model->id;
        }
    }

    public function save(): void
    {
        $validator = Validator::make([
            'client_id' => $this->client,
            'user_id' => auth()->user()->id,
            'date' => $this->date,
            'kasa_id' => $this->kasa_id,
            'price' => $this->price,
        ], [
            'client_id' => 'required',
            'user_id' => 'required',
            'date' => 'required',
            'kasa_id' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        \App\Actions\User\CheckClientBranchAction::run($this->client);

        if (\App\Actions\User\CheckUserInstantApprove::run(auth()->user()->id)) {
            \App\Actions\Kasa\CreateTahsilatAction::run($validator->validated());
            
            $this->success('Tahsilat alındı.');
            //$this->reset('client', 'kasa_id', 'date', 'price');
        } else {
            \App\Actions\User\CreateApproveRequestAction::run($validator->validated(), auth()->user()->id, \App\ApproveTypes::create_tahsilat, '');

            $this->success(\App\Peren::$approve_request_ok);
        }
    }
};

?>
<div>
    <x-card title="Tahsilat" separator progress-indicator>
        <x-form wire:submit="save">
            <livewire:components.form.client_dropdown wire:model.live="client"/>
            @if($client_model != null)
                <livewire:components.client.client_taksit_dropdown :client_id="$client_model->id"/>
                <x-datepicker label="Tarih" wire:model="date"
                              icon="o-calendar" :config="$date_config"/>
                <livewire:components.form.kasa_dropdown wire:model="kasa_id" :branch="[$client_model->branch_id]"/>
                <x-input label="Tutar" wire:model="price" suffix="₺" money/>
            @endif
            <x:slot:actions>
                <x-button label="Gönder" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary"/>
            </x:slot:actions>
        </x-form>
    </x-card>
</div>
