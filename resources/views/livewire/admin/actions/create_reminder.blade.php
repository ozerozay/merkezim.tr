<?php

use Carbon\Carbon;

new
#[\Livewire\Attributes\Title('Hatırlatma Oluştur')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    public $client = null;

    public $config2;

    public $date = null;

    public $message;

    public $name;

    public $branch;

    public $type = 'reminder';

    public array $typeList = [
        [
            'id' => 'reminder',
            'name' => 'Hatırlatma',
        ],
        [
            'id' => 'appointment',
            'name' => 'Randevu',
        ],

    ];

    public function mount(): void
    {
        $this->config2 = \App\Peren::dateConfig(enableTime: true, min: date('Y-m-d H:i'));
        $this->date = date('Y-m-d H:i');
    }

    public function save(): void
    {

        $validator = \Illuminate\Support\Facades\Validator::make([
            'client_id' => $this->client,
            'name' => $this->name,
            'type' => $this->type,
            'date' => Carbon::createFromFormat('Y-m-d H:i', $this->date)->format('Y-m-d'),
            'date_create' => Carbon::createFromFormat('Y-m-d H:i', $this->date)->format('Y-m-d'),
            'time' => Carbon::createFromFormat('Y-m-d H:i', $this->date)->format('H:i:s'),
            'message' => $this->message,
            'branch_id' => $this->branch,
            'user_id' => auth()->user()->id
        ], [
            'client_id' => 'nullable|exists:users,id',
            'name' => 'required',
            'type' => 'required',
            'date' => 'required',
            'date_create' => 'required',
            'time' => 'required',
            'message' => 'required',
            'branch_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        \App\Actions\Agenda\CreateReminderAction::run($validator->validated());

        $this->success('Oluşturuldu.');
        $this->reset('client', 'name', 'type', 'date', 'message', 'branch');

    }
};

?>
<div>
    <x-card title="Hatırlatma Oluştur" progress-indicator separator>
        <x-form wire:submit="save">
            <livewire:components.form.client_dropdown label="Danışan - Boş bırakabilirsiniz." wire:model="client"/>
            <x-hr/>
            <livewire:components.form.branch_dropdown wire:model="branch"/>
            <x-input label="Adı" wire:model="name"/>
            <x-select label="Çeşit" wire:model="type" :options="$typeList"/>
            <x-datepicker label="Tarih" wire:model="date" icon="o-calendar" :config="$config2"/>
            <x-input label="Notunuz" wire:model="message"/>
            <x:slot:actions>
                <x-button label="Gönder" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary"/>
            </x:slot:actions>
        </x-form>
    </x-card>
</div>
