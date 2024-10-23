<?php

use App\Actions\Client\CreateManuelTaksitAction;
use App\Actions\User\CheckClientBranchAction;
use App\Actions\User\CheckUserInstantApprove;
use App\Actions\User\CreateApproveRequestAction;
use App\ApproveTypes;
use App\Peren;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Taksit Oluştur')]
class extends Component
{
    use Toast;

    #[Url(as: 'client')]
    public $client_id = null;

    public ?Collection $selected_taksits;

    public $message = null;

    public $sale_id = null;

    public function mount()
    {
        $this->selected_taksits = collect();
    }

    #[On('client-selected')]
    public function clientSelected($client)
    {
        $this->selected_taksits = collect();
        if ($client != null) {
            $this->dispatch('card-taksit-client-selected', $client)->to('components.card.taksit.card_taksit_select');
        }
    }

    #[On('card-taksit-selected-taksits-updated')]
    public function clientSelectedTaksitsUpdated($taksits)
    {
        $this->selected_taksits = collect($taksits);
    }

    public function save()
    {

        $validator = Validator::make([
            'client_id' => $this->client_id,
            'sale_id' => $this->sale_id,
            'taksits' => $this->selected_taksits->toArray(),
            'message' => $this->message,
            'user_id' => auth()->user()->id,
        ], [
            'client_id' => 'required|exists:users,id',
            'sale_id' => 'nullable|exists:sale,id',
            'taksits' => 'required|array',
            'message' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CheckClientBranchAction::run($this->client_id);

        if (CheckUserInstantApprove::run(auth()->user()->id)) {
            CreateManuelTaksitAction::run($validator->validated());

            $this->success('Taksit oluşturuldu.');
        } else {
            CreateApproveRequestAction::run($validator->validated(), auth()->user()->id, ApproveTypes::create_taksit, $this->message);

            $this->success(Peren::$approve_request_ok);
        }

    }
};

?>

<div>
    <x-card title="Taksit Oluştur" progress-indicator separator>
        <div class="grid lg:grid-cols-2 gap-8">
            <x-form wire:submit='save'>
            <livewire:components.form.client_dropdown label="Danışan" wire:model.live="client_id" />
            @if ($client_id)
            <livewire:components.client.client_sale_dropdown label="Satış" label="Satış - Zorunlu değil"  wire:model="sale_id" :client_id="$client_id" />
            @endif
            <x-input label="Açıklama" wire:model="message" />
            </x-form>
            @if ($client_id)
            <livewire:components.card.taksit.card_taksit_select wire:model="selected_taksits" :client="$client_id" />
            @endif
        </div>
        <x:slot:actions>
            <x-button label="Gönder" wire:click="save" spinner="save" icon="o-paper-airplane" class="btn-primary" />
        </x:slot:actions>
    </x-card>
</div>