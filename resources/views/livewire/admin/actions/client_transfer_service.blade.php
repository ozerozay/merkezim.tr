<?php

use App\Actions\Client\GetClientByUniqueID;
use App\Actions\Client\TransferServiceAction;
use App\Actions\User\CheckClientBranchAction;
use App\Actions\User\CheckUserInstantApprove;
use App\Actions\User\CreateApproveRequestAction;
use App\ApproveTypes;
use App\Peren;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Hizmet Aktar')]
class extends Component
{
    use Toast;

    #[Url]
    public $client = null;

    public $client_model = null;

    public $aktar_model = null;

    public $client_services = [];

    public $aktar_client = null;

    public $aktar_client_sale = null;

    public $seans = 1;

    public $message = null;

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
        if ($client == $this->aktar_client) {
            $this->error('Aynı kişiye aktaramazsınız.');

            $this->client = null;
            $this->client_model = null;
            $this->aktar_client = null;
            $this->aktar_model = null;

            return;
        }
        $this->client_model = GetClientByUniqueID::run(null, $client);

        if ($this->client_model) {
            $this->client = $this->client_model->id;
            $this->client_services = [];
        }
        $this->dispatch('reload-services', ['client' => $this->client])->to('components.client.client_service_multi_dropdown');
    }

    #[On('aktar-selected')]
    public function aktarSelected($client = null)
    {
        if ($client == $this->client) {
            $this->error('Aynı kişiye aktaramazsınız.');

            $this->aktar_model = null;
            $this->aktar_client = null;

            return;
        }
        $this->aktar_model = GetClientByUniqueID::run(null, $client);

        if ($this->aktar_model) {
            $this->aktar_client = $this->aktar_model->id;
            $this->aktar_client_sale = null;
        }
        $this->dispatch('reload-sales', ['client' => $this->client])->to('components.client.client_sale_dropdown');
    }

    public function save()
    {
        $validator = Validator::make([
            'client_id' => $this->client,
            'service_ids' => $this->client_services,
            'seans' => $this->seans,
            'aktar_client_id' => $this->aktar_client,
            'aktar_sale_id' => $this->aktar_client_sale,
            'message' => $this->message,
            'user_id' => auth()->user()->id,
        ], [
            'client_id' => 'required|exists:users,id',
            'service_ids' => 'required|array',
            'seans' => 'required|integer',
            'aktar_client_id' => 'required|exists:users,id',
            'aktar_sale_id' => 'nullable|exists:sale,id',
            'message' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CheckClientBranchAction::run($this->client);

        if (CheckUserInstantApprove::run(auth()->user()->id)) {
            TransferServiceAction::run($validator->validated());

            $this->success('Hizmetler transfer edildi.');
        } else {
            CreateApproveRequestAction::run($validator->validated(), auth()->user()->id, ApproveTypes::client_transfer_service, $this->message);

            $this->success(Peren::$approve_request_ok);
        }

        $this->reset('client', 'aktar_client', 'client_services', 'message');
    }
};

?>
<div>
    <x-card title="Hizmet Aktar" progress-indicator separator>
        <x-form wire:submit="save">
            <livewire:components.form.client_dropdown label="Danışan - Hizmeti Aktarılacak Olan" wire:model.live="client" />
            @if ($client_model != null)
            <livewire:components.client.client_service_multi_dropdown wire:model="client_services" :client_id="$client" />
            <livewire:components.form.number_dropdown wire:model="seans" label="Adet" :includeZero="false" />
            <hr />
            <livewire:components.form.client_dropdown dis="aktar-selected" label="Danışan - Hizmet Aktarılacak Olan" wire:model.live="aktar_client" />
            @if ($aktar_model)
            <livewire:components.client.client_sale_dropdown wire:model="aktar_client_sale" :client_id="$aktar_client" />
            <x-input label="Açıklama" wire:model="message" />
            @endif
            @endif
            <x:slot:actions>
                <x-button label="Gönder" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary" />
            </x:slot:actions>
        </x-form>
    </x-card>
</div>