<?php

use App\Traits\LiveHelper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
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
        $this->dispatch('card-service-client-selected', $client)->to('components.card.service.card_service_select');
        $this->dispatch('card-cash-client-selected', $client)->to('components.card.cash.card_cash_select');
    }

    public function totalPrice()
    {
        if ($this->price > 0) {
            return $this->price;
        }
        $totalP = 0.0;
        foreach ($this->selected_services as $s) {
            $totalP += $s['price'] * $s['quantity'];
        }

        return $totalP;
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
            <x-button label="Gönder" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary" />
        </x:slot:actions>
    </x-card>
</div>