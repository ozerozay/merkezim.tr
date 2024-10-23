<?php

use App\Actions\Offer\CreateOfferAction;
use App\Actions\User\CheckClientBranchAction;
use App\Actions\User\CheckUserInstantApprove;
use App\Actions\User\CreateApproveRequestAction;
use App\ApproveTypes;
use App\Peren;
use App\Traits\LiveHelper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Teklif Oluştur')]
class extends Component
{
    use Toast;

    #[Url(as: 'client')]
    public $client_id = null;

    public $price = 0.0;

    public $expire_date;

    public $message;

    public $month = 0;

    public $config_expire_date = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    public ?Collection $selected_services;

    public function mount()
    {
        LiveHelper::class;
        $this->config_expire_date['minDate'] = Carbon::now()->addDay(1)->format('Y-m-d');
        $this->selected_services = collect();
    }

    #[On('client-selected')]
    public function client_selected($client)
    {
        $this->selected_services = collect();
        if ($client != null) {
            $this->dispatch('card-service-client-selected', $client)->to('components.card.service.card_service_select');
        }
    }

    public function totalPrice()
    {
        return $this->selected_services->sum(function ($q) {
            return $q['gift'] ? 0 : $q['price'] * $q['quantity'];
        });
    }

    #[On('card-service-selected-services-updated')]
    public function selectedServicesUpdate($selected_services)
    {
        $this->selected_services = collect($selected_services);
    }

    public function save()
    {
        if ($this->selected_services->isEmpty()) {
            $this->error('Hizmet seçmelisiniz.');

            return;
        }

        $validator = Validator::make([
            'client_id' => $this->client_id,
            'price' => $this->price,
            'expire_date' => $this->expire_date,
            'message' => $this->message,
            'month' => $this->month,
            'services' => $this->selected_services->toArray(),
            'user_id' => auth()->user()->id,
        ], [
            'client_id' => 'required|exists:users,id',
            'price' => 'required|decimal:0,2|min:1',
            'expire_date' => 'nullable|after:today',
            'message' => 'required',
            'month' => 'required',
            'services' => 'required|array',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CheckClientBranchAction::run($this->client_id);

        if (CheckUserInstantApprove::run(auth()->user()->id)) {
            CreateOfferAction::run($validator->validated());

            $this->success('Teklif oluşturuldu.');
        } else {
            CreateApproveRequestAction::run($validator->validated(), auth()->user()->id, ApproveTypes::create_offer, $this->message);

            $this->success(Peren::$approve_request_ok);
        }

    }
};

?>
<div>
    <x-card title="Teklif Oluştur" progress-indicator separator>
        <div class="grid lg:grid-cols-2 gap-8">
            <x-form wire:submit="save">
                <livewire:components.form.client_dropdown label="Danışan" wire:model.live="client_id" />
                <x-input label="Tutar" wire:model="price" suffix="₺" money />
                <x-datepicker label="Son Geçerlilik Tarihi (Sınırsız ise boş bırakın)" wire:model="expire_date"
                    icon="o-calendar" :config="$config_expire_date" />
                <livewire:components.form.number_dropdown label="Paket Kullanım Süresi (Sınırsız ise 0)"
                    includeZero="true" suffix="Ay" wire:model="month" />
                <x-input label="Açıklama" wire:model="message" />
            </x-form>
            @if ($client_id)
            <livewire:components.card.service.card_service_select wire:model="selected_services" :client="$client_id" />
            @endif
        </div>
        <x:slot:actions>
            <x-button label="Gönder" wire:click="save" spinner="save" icon="o-paper-airplane" class="btn-primary" />
        </x:slot:actions>
    </x-card>
</div>