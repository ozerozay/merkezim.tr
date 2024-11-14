<?php

use App\Actions\Client\GetClientByUniqueID;
use App\Actions\Client\UpdateClientLabels;
use App\Actions\User\CheckClientBranchAction;
use App\Actions\User\CheckUserInstantApprove;
use App\Actions\User\CreateApproveRequestAction;
use App\ApproveTypes;
use App\Peren;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Etiket Belirle')]
class extends Component {
    use Toast;

    #[Url]
    public $client = null;

    public $client_model = null;

    public $client_labels = [];

    public function mount(): void
    {
        $this->client_model = GetClientByUniqueID::run(null, $this->client, ['labels']);

        if ($this->client_model) {
            $this->client = $this->client_model->id;
            $this->client_labels = $this->client_model->labels ?? [];
        }
    }

    #[On('client-selected')]
    public function clientSelected($client = null)
    {
        $this->client_model = GetClientByUniqueID::run(null, $client, ['labels']);

        if ($this->client_model) {
            $this->client = $this->client_model->id;
            $this->client_labels = $this->client_model->labels;
        }
    }

    public function save(): void
    {
        $validator = Validator::make([
            'client_id' => $this->client,
            'labels' => $this->client_labels,
        ], [
            'client_id' => 'required|exists:users,id',
            'labels' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CheckClientBranchAction::run($this->client);

        if (CheckUserInstantApprove::run(auth()->user()->id)) {
            UpdateClientLabels::run($validator->validated());
            $this->success('Etiketler güncellendi.');
        } else {
            CreateApproveRequestAction::run($validator->validated(), auth()->user()->id, ApproveTypes::client_update_label, '');

            $this->success(Peren::$approve_request_ok);
        }

    }
}

?>
<div>
    <x-card title="Etiket Belirle" progress-indicator separator>
        <x-form wire:submit="save">
            <livewire:components.form.client_dropdown wire:model.live="client"/>
            @if ($client_model != null)
                <livewire:components.client.client_label_multi_dropdown wire:model="client_labels"
                                                                        :client_id="$client"/>
            @endif
            <x:slot:actions>
                <x-button label="Gönder" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary"/>
            </x:slot:actions>
        </x-form>
    </x-card>
</div>
