<?php

use App\Actions\Adisyon\CreateAdisyonAction;
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
#[Title('Adisyon Oluştur')]
class extends Component
{
    use Toast;

    #[Url(as: 'client')]
    public $client_id = null;

    public $adisyon_date;

    public $staff_ids = [];

    public float $price = 0.0;

    public $message = null;

    public $branch = [];

    public $config_adisyon_date = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    public ?Collection $selected_services;

    public ?Collection $selected_cash;

    public function mount()
    {
        LiveHelper::class;
        $this->config_adisyon_date['maxDate'] = Carbon::now()->format('Y-m-d');
        $this->adisyon_date = Carbon::now()->format('Y-m-d');
        $this->selected_services = collect();
        $this->selected_cash = collect();
    }

    public function updatedPrice($price)
    {
        $this->dispatchMaxPriceChanged();
    }

    #[On('client-selected')]
    public function client_selected($client)
    {
        $this->selected_services = collect();
        $this->selected_cash = collect();
        if ($client != null) {
            $this->dispatch('card-service-client-selected', $client)->to('components.card.service.card_service_select');
            $this->dispatch('card-cash-client-selected', $client)->to('components.card.cash.card_cash_select');
        }
    }

    public function totalPrice()
    {
        if ($this->price > 0) {
            return $this->price;
        }

        return $this->selected_services->sum(function ($q) {
            return $q['gift'] ? 0 : $q['price'] * $q['quantity'];
        });
    }

    #[On('card-service-selected-services-updated')]
    public function selectedServicesUpdate($selected_services)
    {
        $this->selected_services = collect($selected_services);
        $this->dispatchMaxPriceChanged();
    }

    #[On('card-cash-selected-cash-updated')]
    public function selectedCashUpdate($selected_cash)
    {
        $this->selected_cash = collect($selected_cash);
    }

    public function dispatchMaxPriceChanged()
    {
        $this->dispatch('card-cash-max-price-changed', $this->totalPrice())->to('components.card.cash.card_cash_select');
    }

    public function totalCashPrice()
    {
        return $this->selected_cash->sum('price');
    }

    public function save()
    {
        if ($this->selected_services->count() == 0) {
            $this->error('Hizmet seçmelisiniz.');

            return;
        }

        if ($this->totalPrice() > $this->totalCashPrice()) {
            $this->error('Tüm tutarı yapılandırmanız gerekiyor.'.($this->totalPrice() - $this->totalCashPrice()).'₺');

            return;
        }

        $validator = Validator::make([
            'client_id' => $this->client_id,
            'adisyon_date' => $this->adisyon_date,
            'message' => $this->message,
            'price' => $this->totalPrice(),
            'staff_ids' => $this->staff_ids,
            'services' => $this->selected_services->toArray(),
            'cashes' => $this->selected_cash->toArray(),
            'user_id' => auth()->user()->id,
        ], [
            'client_id' => 'nullable|exists:users,id',
            'adisyon_date' => 'required|before:tomorrow',
            'message' => 'required',
            'price' => 'required|decimal:0,2',
            'staff_ids' => 'nullable|array',
            'services' => 'required|array',
            'cashes' => 'nullable|array',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CheckClientBranchAction::run($this->client_id);

        if (CheckUserInstantApprove::run(auth()->user()->id)) {
            CreateAdisyonAction::run($validator->validated());

            $this->success('Adisyon oluşturuldu.');
        } else {
            CreateApproveRequestAction::run($validator->validated(), auth()->user()->id, ApproveTypes::create_adisyon, $this->message);

            $this->success(Peren::$approve_request_ok);
        }

    }
};

?>
<div>
    <x-card title="Adisyon Oluştur" progress-indicator separator>
        <div class="grid lg:grid-cols-3 gap-8">
            <x-form wire:submit="save">
                <livewire:components.form.client_dropdown label="Danışan - Zorunlu değil" wire:model.live="client_id" />
                <x-datepicker label="Tarih" wire:model="adisyon_date" icon="o-calendar"
                    :config="$config_adisyon_date" />
                <livewire:components.form.staff_multi_dropdown wire:model="staff_ids" />
                @can('change_sale_price')
                <x-input label="Manuel Satış Tutarı"
                wire:model.live.debounce.500ms="price" suffix="₺" money />
                @endcan
                <x-input label="Adisyon notunuz" wire:model="message" />
            </x-form>
            
            <livewire:components.card.service.card_service_select wire:model="selected_services" :client="$client_id" />
            <livewire:components.card.cash.card_cash_select wire:model="selected_cash" :client="$client_id" />
        </div>
        <x:slot:actions>
            <x-button label="Gönder" wire:click="save" spinner="save" icon="o-paper-airplane" class="btn-primary" />
        </x:slot:actions>
    </x-card>
</div>