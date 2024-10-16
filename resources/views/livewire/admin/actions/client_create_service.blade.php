<?php

use App\Actions\Client\CreateServicesAction;
use App\Actions\Client\GetClientByUniqueID;
use App\Actions\User\CheckClientBranchAction;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Hizmet Yükle')]
class extends Component
{
    use Toast;

    #[Url]
    public $client = null;

    public $client_model = null;

    public $service_ids = [];

    public $sale_id = null;

    public $gender = null;

    public $branch = null;

    public $seans = 1;

    public $gift = false;

    public $message = null;

    public function mount()
    {
        $this->client_model = GetClientByUniqueID::run(null, $this->client);
        if ($this->client_model) {
            $this->client = $this->client_model->id;
            $this->gender = $this->client_model->gender;
            $this->branch = $this->client_model->branch_id;
        }

    }

    #[On('client-selected')]
    public function clientSelected($client = null)
    {
        $this->client_model = GetClientByUniqueID::run(null, $client);
        if ($this->client_model) {
            $this->client = $this->client_model->id;
            $this->gender = $this->client_model->gender;
            $this->branch = $this->client_model->branch_id;
        }
        $this->sale_id = null;
        $this->dispatch('reload-sales', ['client' => $this->client])->to('components.client.client_sale_dropdown');
    }

    public function save()
    {

        $validator = Validator::make([
            'client_id' => $this->client,
            'branch_id' => $this->client_model->branch_id,
            'service_ids' => $this->service_ids,
            'sale_id' => $this->sale_id,
            'total' => $this->seans,
            'remaining' => $this->seans,
            'gift' => $this->gift,
            'message' => $this->message,

        ], [
            'client_id' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'service_ids' => 'required|array',
            'sale_id' => 'nullable|exists:sale,id',
            'total' => 'required|integer',
            'remaining' => 'required|integer',
            'gift' => 'required|boolean',
            'message' => 'nullable',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CheckClientBranchAction::run($this->client);

        CreateServicesAction::run($validator->validated(), auth()->user()->id);

        $this->success('Hizmetler eklendi.');

        $this->reset('service_ids', 'message');
    }
};
?>

<div>
    <x-card title="Hizmet Yükle" progress-indicator separator>
    <x-form wire:submit="save">
        <livewire:components.form.client_dropdown wire:model.live="client" />
        @if ($client != null)
        <livewire:components.client.client_sale_dropdown wire:model="sale_id" :client_id="$client" />
        <livewire:components.form.service_multi_dropdown wire:model="service_ids" :branch_id="$branch" :gender="$gender" />
        <livewire:components.form.number_dropdown wire:model="seans" label="Adet" :includeZero="false" />
        <x-input label="Açıklama" wire:model="message" />
        <x-checkbox label="Hediye" wire:model="gift" />
        @endif
    <x:slot:actions>
        <x-button label="Gönder" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary" />
    </x:slot:actions>
    </x-form>
    </x-card>
</div>