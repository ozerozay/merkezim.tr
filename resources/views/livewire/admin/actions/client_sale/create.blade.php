<?php

use App\Actions\Sale\CreateSaleAction;
use App\Exceptions\AppException;
use App\Models\Package;
use App\Models\Service;
use App\Traits\LiveHelper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Satış Oluştur')]
class extends Component {
    use Toast;

    #[Url(as: 'client')]
    public $client_id = null;

    public $sale_type_id = null;

    public $sale_date = null;

    public $staff_ids = [];

    public $message = null;

    public $branch = null;

    public $price = 0;

    public $expire_date = 0;

    public $selected_services;

    public $selected_cash;

    public $selected_taksits;

    public $config_sale_date = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    public $config_taksit_date = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    public function mount()
    {
        //Carbon::now();
        //LiveHelper::class;
        $this->config_sale_date['maxDate'] = Carbon::now()->format('Y-m-d');
        $this->config_taksit_date['minDate'] = Carbon::now()->format('Y-m-d');
        $this->sale_date = Carbon::now()->format('Y-m-d');
        $this->selected_services = collect();
        $this->selected_cash = collect();
        $this->selected_taksits = collect();

    }

    #[On('client-selected')]
    public function client_selected($client)
    {
        $this->selected_services = collect();
        $this->selected_cash = collect();
        $this->selected_taksits = collect();
        if ($client != null) {
            $this->dispatch('card-service-client-selected', $client)->to('components.card.service.card_service_select');
            $this->dispatch('card-cash-client-selected', $client)->to('components.card.cash.card_cash_select');
        }
    }

    public function updatedPrice($price)
    {
        $this->dispatchMaxPriceChanged();
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
        $this->dispatchMaxPriceChangedTaksit();
    }

    #[On('card-sale-taksit-selected-taksit-updated')]
    public function selectedTaksitUpdate($selected_taksits)
    {
        $this->selected_taksits = collect($selected_taksits);
    }

    public function dispatchMaxPriceChanged()
    {
        $this->dispatch('card-cash-max-price-changed', $this->totalPrice())->to('components.card.cash.card_cash_select');

    }

    public function dispatchMaxPriceChangedTaksit()
    {
        $this->dispatch('card-sale-taksit-max-price-changed', $this->totalPrice() - $this->totalCashPrice())->to('components.card.sale_taksit.card_sale_taksit_select');
    }

    public function totalCashPrice()
    {
        return $this->selected_cash->sum('price');
    }

    /*

    #[On('service-added')]
    public function service_add($info)
    {
        $services = Service::whereIn('id', $info['service_id'])->get();

        foreach ($services as $s) {
            if ($this->selected_services->count() > 0 && ($info['gift'] != true) && $this->selected_services->contains(function ($q) use ($s) {
                return $q['type'] == 'service' && $q['service_id'] == $s->id;
            })) {
                $this->error($s->name.' bulunuyor, değişikliği tablodan yapın.');
                break;
            }

            $lastId = $this->selected_services->last() != null ? $this->selected_services->last()['id'] + 1 : 1;
            $this->selected_services->push([
                'id' => $lastId,
                'service_id' => $s->id,
                'type' => 'service',
                'name' => $s->name,
                'gift' => $info['gift'],
                'quantity' => $info['quantity'],
                'price' => $s->price,
            ]);
        }
        //dump($this->selected_services);
    }

    #[On('package-added')]
    public function package_add($info)
    {
        $package = Package::where('id', $info['package_id'])->first();

        if ($package) {
            if (($info['gift'] != true) && $this->selected_services->contains(function ($q) use ($package) {
                return $q['type'] == 'package' && $q['package_id'] == $package->id;
            })) {
                $this->error($package->name.' bulunuyor, değişikliği tablodan yapın.');

                return;

            }

            $lastId = $this->selected_services->last() != null ? $this->selected_services->last()['id'] + 1 : 1;

            $this->selected_services->push([
                'id' => $lastId,
                'package_id' => $package->id,
                'type' => 'package',
                'name' => $package->name,
                'gift' => $info['gift'],
                'quantity' => $info['quantity'],
                'price' => $package->price,
            ]);
        }
    }

    public function deleteItem($id, $type)
    {
        $this->selected_services = $this->selected_services->reject(function ($item) use ($id, $type) {
            return $item['id'] == $id && $item['type'] == $type;
        });
    }

    public function updateQuantity($id, $value)
    {
        $this->selected_services->transform(function ($item) use ($id, $value) {
            if ($item['id'] == $id) {
                $item['quantity'] = $value;
            }

            return $item;
        });
    }

    public function quantities(): Collection
    {
        $items = collect();

        collect(range(1, 100))->each(fn ($item) => $items->add(['id' => $item, 'name' => $item]));

        return $items;
    }

    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Ad'],
            ['key' => 'quantity', 'label' => 'Adet'],
        ];
    }

    public function headers_nakit(): array
    {
        return [
            ['key' => 'date', 'label' => 'Tarih'],
            ['key' => 'kasa', 'label' => 'Kasa'],
            ['key' => 'price', 'label' => 'Tutar'],
        ];
    }

    public function headers_taksit(): array
    {
        return [
            ['key' => 'date', 'label' => 'Tarih'],
            ['key' => 'price', 'label' => 'Tutar'],
        ];
    }

    public function with(): array
    {
        return [
            'selected_services' => $this->selected_services,
            'headers' => $this->headers(),
            'quantities' => $this->quantities(),
            'headers_nakit' => $this->headers_nakit(),
            'headers_taksit' => $this->headers_taksit(),
            //'pay_nakit' => $this->pay_nakit,
        ];
    }

    public function reset_form()
    {
        $this->price = 0;
        $this->expire_date = null;
        $this->month = 0;
    }

    #[Computed]
    public function yapilandirilacak()
    {
        return $this->price > 0 ? $this->price : $this->totalPrice();
    }

    #[Computed]
    public function yapilandirilan()
    {
        if ($this->selected_taksits->count() > 0) {
            return $this->yapilandirilacak();
        }

        $tutar = 0.0;

        foreach ($this->selected_cash as $p) {
            $tutar += $p['price'];
        }

        return $tutar;

    }

    #[Computed]
    public function kalan()
    {
        return $this->yapilandirilacak() - $this->yapilandirilan();
    }

    public function delete_pay($id)
    {
        //dump($id);
        $this->pay_nakit = $this->pay_nakit->reject(function ($item) use ($id) {
            return $item['id'] == $id;
        });
    }

    #[On('nakit-added')]
    public function add_pay($info)
    {
        if ($info['pay_price'] == 0) {
            $this->error('Tutar girmelisiniz.');

            return;
        }

        if ($info['pay_kasa_id'] == null) {
            $this->error('Kasa seçmelisiniz.');

            return;
        }

        if ($info['pay_date'] == null) {
            $this->error('Tarih seçmelisiniz.');

            return;
        }

        if ($info['pay_price'] > $this->kalan()) {
            $this->error($this->kalan()." TL'den fazla olamaz.");

            return;
        }

        $lastId = $this->pay_nakit->last() != null ? $this->pay_nakit->last()['id'] + 1 : 1;

        $this->pay_nakit->push([
            'id' => $lastId,
            'date' => $info['pay_date'],
            'kasa' => $info['pay_kasa_id'],
            'price' => $info['pay_price'],
        ]);

        //dump($this->pay_nakit);
        $this->success('Peşinat eklendi.');

    }

    public function cancel_taksit()
    {
        $this->taksits = collect();
    }

    #[On('change-taksit-date')]
    #[Renderless]
    public function change_taksit_date($info)
    {
        $this->taksits->transform(function ($item) use ($info) {
            if ($item['id'] == $info['t_id']) {
                $item['date'] = Carbon::parse($info['taksit_date'])->format('d/m/Y');
            }

            return $item;
        });
    }



        */

    public function change_section()
    {
        //throw new AppException('You can not delete this demo item, add a new one.');
        if ($this->section == 1) {
            if ($this->price < 1 && $this->totalPrice() < 1) {
                $this->error('Satış tutarı sıfırdan büyük olmalı.');

                return;
            }

            if ($this->client_id == null) {
                $this->error('Danışan seçmelisiniz.');

                return;
            }

            if ($this->sale_type_id == null) {
                $this->error('Satış tipi seçmelisiniz.');

                return;
            }

            if ($this->sale_date == null) {
                $this->error('Satış tarihi seçmelisiniz.');

                return;
            }

            if ($this->message == null) {
                $this->error('Açıklama girmelisiniz.');

                return;
            }

            $this->dispatchMaxPriceChanged();
            $this->dispatchMaxPriceChangedTaksit();

            $this->section = 2;

        }
    }

    public function backService()
    {
        $this->selected_taksits = collect();
        $this->selected_cash = collect();
        $this->section = 1;
    }

    public int $section = 1;

    public function save_sale()
    {
        $validator = Validator::make([
            'client_id' => $this->client_id,
            'sale_type_id' => $this->sale_type_id,
            'sale_date' => $this->sale_date,
            'staff_ids' => $this->staff_ids,
            'sale_price' => $this->price,
            'services' => $this->selected_services,
            'nakits' => $this->selected_cash,
            'taksits' => $this->selected_taksits,
            'price_real' => $this->totalPrice(),
            'message' => $this->message,
            'user_id' => auth()->user()->id,
            'expire_date' => $this->expire_date,
        ], [
            'client_id' => 'required|exists:users,id',
            'sale_type_id' => 'required|exists:sale_types,id',
            'sale_date' => 'required|before:tomorrow',
            'staff_ids' => 'nullable|array',
            'sale_price' => 'nullable|decimal:0,2',
            'services' => 'required',
            'nakits' => 'nullable',
            'taksits' => 'nullable',
            'price_real' => 'decimal:0,2',
            'message' => 'required',
            'user_id' => 'required',
            'expire_date' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateSaleAction::run($validator->validated());

        $this->success('Satış oluşturuldu.');

        return redirect()
            ->route('admin.client.profil.index', ['user' => $validator->validated()['client_id']]);
    }
};
?>

<div>
    @if ($section == 1)
        <x-card title="Satış Oluştur" separator shadow>
            <div class="grid lg:grid-cols-2 gap-8">
                <x-card title="Danışan ve Satış" separator progress-indicator shadow>
                    <x-form>
                        <livewire:components.form.client_dropdown wire:model.live="client_id"/>
                        <div class="grid lg:grid-cols-2 gap-8">
                            <livewire:components.form.sale_type_dropdown wire:model="sale_type_id"/>
                            <x-datepicker label="Satış Tarihi" wire:model="sale_date" icon="o-calendar"
                                          :config="$config_sale_date"/>
                        </div>

                        <livewire:components.form.staff_multi_dropdown wire:model="staff_ids"/>
                        <x-input label="Satış Tutarı (Tutarı değiştirmek istemiyorsanız 0 bırakın.)"
                                 wire:model.live.debounce.500ms="price" suffix="₺" money/>
                        <livewire:components.form.number_dropdown suffix="AY"
                                                                  label="Paket Kullanım Süresi (Ay) - 0 sınırsız"
                                                                  includeZero="true" wire:model="expire_date"/>
                        <x-input label="Satış notunuz" wire:model="message"/>
                    </x-form>
                </x-card>
                @if($client_id)
                    <livewire:components.card.service.card_service_select wire:model="selected_services"
                                                                          :client="$client_id"/>
                @endif
            </div>
            <x:slot:actions>
                @if ($this->totalPrice() > 0 || $this->price > 1)
                    <x-button label="Ödeme Bölümüne Geç" icon="o-credit-card" wire:click="change_section" spinner
                              class="btn-primary"/>
                @else
                    <x-button label="Hizmet ekleyin" icon="o-credit-card" disabled spinner
                              class="btn-primary"/>
                @endif
            </x:slot:actions>
        </x-card>
    @else
        <x-card title="Satış" separator shadow>
            <div class="grid lg:grid-cols-2 gap-8">
                <livewire:components.card.cash.card_cash_select wire:model="selected_cash" :client="$client_id"
                                                                :maxPrice="$this->totalPrice()"/>
                <livewire:components.card.sale_taksit.card_sale_taksit_select wire:model="selected_taksits"
                                                                              :maxPrice="$this->totalPrice() - $this->totalCashPrice()"/>
            </div>
            <x:slot:actions>
                <x-button icon="o-arrow-uturn-left" spinner label="Geri Dön" wire:click="backService"
                          class="btn-ghost text-warning btn-sm"
                          wire:confirm="Ödeme yapılandırması iptal edilecektir, geri dönmek istiyor musunuz ?"/>
                <x-button icon="o-check" spinner label="Satışı Tamamla" class="btn-primary btn-sm"
                          wire:click="save_sale"/>
            </x:slot:actions>
        </x-card>
    @endif

</div>
