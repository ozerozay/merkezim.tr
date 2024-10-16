<?php

use App\Actions\Client\GetClientByUniqueID;
use App\Actions\Client\UseServiceAction;
use App\Actions\User\CheckClientBranchAction;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Hizmet Kullandır')]
class extends Component
{
    use Toast;

    #[Url]
    public $client = null;

    public $client_model = null;

    public $service_ids = [];

    public $message = null;

    public $seans = 1;

    public function mount()
    {
        $this->client_model = GetClientByUniqueID::run(null, $this->client);

        if ($this->client_model) {
            $this->client = $this->client_model->id;
        }
    }

    #[On('client-selected')]
    public function clientSelected($client = null)
    {
        $this->client_model = GetClientByUniqueID::run(null, $client);

        if ($this->client_model) {
            $this->client = $this->client_model->id;
            $this->service_ids = [];
        }
    }

    public function save()
    {

        $validator = Validator::make([
            'client_id' => $this->client,
            'service_ids' => $this->service_ids,
            'message' => $this->message,
            'seans' => $this->seans,
            'user_id' => auth()->user()->id,
        ], [
            'client_id' => 'required|exists:users,id',
            'service_ids' => 'required|array',
            'message' => 'required',
            'seans' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CheckClientBranchAction::run($this->client);

        UseServiceAction::run($validator->validated());

        $this->success('Hizmetler kullandırıldı.');

        $this->reset('service_ids', 'message');

    }
};

?>

<div>
    <x-card title="Hizmet Kullandır" progress-indicator separator>
        <x-form wire:submit="save">
        <livewire:components.form.client_dropdown wire:model.live="client" />
        @if ($client != null)
        <livewire:components.client.client_service_multi_dropdown wire:model="service_ids" :client_id="$client" />
        <livewire:components.form.number_dropdown wire:model="seans" label="Adet" :includeZero="false" />
        <x-input label="Açıklama" wire:model="message" />
        @endif
            <x:slot:actions>
                <x-button label="Gönder" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary" />
            </x:slot:actions>
        </x-form>
    </x-card>
</div>