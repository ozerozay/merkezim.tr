<?php

use App\Actions\SaleProduct\CreateSaleProductAction;
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
#[Title('Ürün Sat')]
class extends Component
{
    use Toast;

    #[Url(as: 'client')]
    public $client_id = null;

    public $sale_date;

    public $staff_ids = [];

    public float $price = 0.0;

    public $message = null;

    public $branch = null;

    public $config_sale_date = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    public ?Collection $selected_products;

    public ?Collection $selected_cash;

    public function mount()
    {
        LiveHelper::class;
        $this->config_sale_date['maxDate'] = Carbon::now()->format('Y-m-d');
        $this->sale_date = Carbon::now()->format('Y-m-d');
        $this->selected_products = collect();
        $this->selected_cash = collect();
    }

    #[On('client-selected')]
    public function client_selected($client)
    {
        $this->selected_products = collect();
        $this->selected_cash = collect();
        $this->dispatch('card-cash-client-selected', $client)->to('components.card.cash.card_cash_select');
        $this->dispatch('card-product-client-selected', $client)->to('components.card.product.card_product_select');
    }

    public function totalPrice()
    {
        if ($this->price > 0) {
            return $this->price;
        }

        return $this->selected_products->sum(function ($q) {
            return $q['gift'] ? 0 : $q['price'] * $q['quantity'];
        });
    }

    public function totalPriceCash()
    {
        return $this->selected_cash->sum('price');
    }

    #[On('card-product-selected-products-updated')]
    public function selectedProductsUpdate($selected_products)
    {
        $this->selected_products = collect($selected_products);
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
        $kalan = $this->totalPrice() - $this->totalCashPrice();

        if ($kalan > 0) {
            $this->error('Tüm tutarı yapılandırmalısınız. Kalan'.$kalan.' TL.');

            return;
        }

        if ($this->selected_products->count() == 0) {
            $this->error('Ürün seçmelisiniz.');

            return;
        }

        $validator = Validator::make([
            'client_id' => $this->client_id,
            'products' => $this->selected_products->toArray(),
            'cashes' => $this->selected_cash->toArray(),
            'sale_date' => $this->sale_date,
            'staff_ids' => $this->staff_ids,
            'message' => $this->message,
            'price' => $this->totalPrice(),
            'user_id' => auth()->user()->id,
        ], [
            'client_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'cashes' => 'nullable|array',
            'sale_date' => 'required|before:tomorrow',
            'staff_ids' => 'nullable|array',
            'message' => 'required',
            'price' => 'required|decimal:0,2',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CheckClientBranchAction::run($this->client_id);

        if (CheckUserInstantApprove::run(auth()->user()->id)) {

            CreateSaleProductAction::run($validator->validated());

            $this->success('Ürün satışı oluşturuldu.');
        } else {
            CreateApproveRequestAction::run($validator->validated(), auth()->user()->id, ApproveTypes::create_product_sale, $this->message);

            $this->success(Peren::$approve_request_ok);
        }

    }
};

?>
<div>
    <x-card title="Ürün Sat" progress-indicator separator>
        <div class="grid lg:grid-cols-3 gap-8">
            <x-form wire:submit="save">
                <livewire:components.form.client_dropdown label="Danışan" wire:model.live="client_id" />
                <x-datepicker label="Tarih" wire:model="sale_date" icon="o-calendar" :config="$config_sale_date" />
                <livewire:components.form.staff_multi_dropdown wire:model="staff_ids" />
                @can('change_sale_price')
                <x-input label="Manuel Satış Tutarı" wire:model.live.debounce.500ms="price" suffix="₺" money />
                @endcan
                <x-input label="Satış notunuz" wire:model="message" />
            </x-form>
            @if ($client_id)
            <livewire:components.card.product.card_product_select wire:model="selected_products" :client="$client_id" />
            <livewire:components.card.cash.card_cash_select wire:model="selected_cash" :client="$client_id" />
            @endif
        </div>
        <x:slot:actions>
            <x-button label="Gönder" wire:click="save" spinner="save" icon="o-paper-airplane" class="btn-primary" />
        </x:slot:actions>
    </x-card>
</div>