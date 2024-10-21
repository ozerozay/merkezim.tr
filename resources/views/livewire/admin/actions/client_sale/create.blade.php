<?php

use App\Actions\Sale\CreateSaleAction;
use App\Exceptions\AppException;
use App\Models\Package;
use App\Models\Service;
use App\Models\User;
use App\Traits\LiveHelper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Satış Oluştur')]
class extends Component
{
    use Toast;

    public $client;

    public $client_id;

    public $sale_type_id = null;

    public $sale_date = null;

    public $staff_ids = [];

    public $message = null;

    public $branch = null;

    public $price = 0;

    public $expire_date = 0;

    public $selected_services;

    public $pay_nakit;

    public $taksits;

    public $config_sale_date = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    public $config_taksit_date = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    protected $queryString = [
        'client',
    ];

    public function mount()
    {
        Carbon::now();
        $this->config_sale_date['maxDate'] = Carbon::now()->format('Y-m-d');
        $this->config_taksit_date['minDate'] = Carbon::now()->format('Y-m-d');
        $this->sale_date = Carbon::now()->format('Y-m-d');
        $this->selected_services = collect();
        $this->pay_nakit = collect();
        $this->taksits = collect();
        //LiveHelper::class
        /*$client_model = User::where('unique_id', $this->client)->first();

        if ($client_model) {
            if (! User::checkClientBranch($client_model->id)) {
                $this->error('Bu danışan için yetkiniz bulunmuyor.');

                return redirect()->route('admin.actions.client_sale_customer');
            } else {
                $this->client_id = $client_model->id;
                $this->branch = $client_model->branch_id;
                $this->config_sale_date['maxDate'] = Carbon::now()->format('Y-m-d');
                $this->sale_date = Carbon::now()->format('Y-m-d');
                $this->selected_services = collect();
            }
        } else {
            return redirect()->route('admin.actions.client_sale_customer');
        }*/
    }

    #[On('client-selected')]
    public function client_selected($client)
    {
        $this->branch = [User::find($client)->branch_id ?? null];
    }

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

    #[Computed()]
    public function totalPrice()
    {
        $totalP = 0.0;
        foreach ($this->selected_services as $s) {
            if (! $s['gift']) {
                $totalP += $s['price'] * $s['quantity'];
            }
        }

        return $totalP;

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
            'pay_nakit' => $this->pay_nakit,
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
        if ($this->taksits->count() > 0) {
            return $this->yapilandirilacak();
        }

        $tutar = 0.0;

        foreach ($this->pay_nakit as $p) {
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

    #[On('taksitlendir')]
    public function add_taksit($info)
    {
        $kalan = $this->kalan();

        $taksit_sayisi = $info['taksit_sayi'];

        $taksit_para = $kalan / $taksit_sayisi;

        $taksit_tarih = $info['taksit_date'];

        $taksit_para_ondalik = number_format($taksit_para, 2, '.', '');

        for ($i = 0; $i < $taksit_sayisi; $i++) {
            $this->taksits->push([
                'id' => $i,
                'price' => $taksit_para_ondalik,
                'date' => Carbon::parse($taksit_tarih)->addMonths($i)->format('d/m/Y'),
            ]);
        }

        $cikan_taksit = 0.0;

        foreach ($this->taksits as $t) {
            $cikan_taksit += $t['price'];
        }

        $eklenecek = $kalan - $cikan_taksit;

        $lastItemKey = $this->taksits->keys()->last();

        $this->taksits = $this->taksits->map(function ($item, $key) use ($lastItemKey, $eklenecek) {
            if ($key === $lastItemKey) {
                return [
                    'id' => $item['id'],
                    'price' => $item['price'] + $eklenecek,
                    'date' => $item['date'],
                ];  // Yeni değer
            }

            return $item;
        });

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

            $this->section = 2;

        }
    }

    public function backService()
    {
        $this->taksits = collect();
        $this->pay_nakit = collect();
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
            'nakits' => $this->pay_nakit,
            'taksits' => $this->taksits,
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
    }
};
?>

<div>
    @if ($section == 1)
    <x-card title="Satış Oluştur" separator shadow>
        <div class="grid lg:grid-cols-2 gap-8">
            <x-card title="Danışan ve Satış" separator progress-indicator shadow>
                <x-form>
                    <livewire:components.form.client_dropdown wire:model.live="client_id" />
                    <livewire:components.form.sale_type_dropdown wire:model="sale_type_id" />
                    <x-datepicker label="Satış Tarihi" wire:model="sale_date" icon="o-calendar"
                        :config="$config_sale_date" />
                    <livewire:components.form.staff_multi_dropdown wire:model="staff_ids" />
                    <x-input label="Satış Tutarı (Tutarı değiştirmek istemiyorsanız 0 bırakın.)"
                        wire:model.live.debounce.500ms="price" suffix="₺" money />
                    <livewire:components.form.number_dropdown suffix="AY" label="Paket Kullanım Süresi (Ay) - 0 sınırsız" includeZero="true" wire:model="expire_date" />
                    <x-input label="Satış notunuz" wire:model="message" />
                </x-form>
            </x-card>
                @if($branch)
                <x-card title="Hizmetler" separator progress-indicator shadow>
                    <x-table :headers="$headers" :rows="$selected_services">
                        @scope('cell_quantity', $item, $quantities)
                        <x-select wire:model.number="selected_services.{{ $loop->index }}.quantity"
                            :options="$quantities" wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)"
                            class="select-sm !w-20" />
                        @endscope
                        @scope('actions', $service)
                        <x-button icon="o-trash" wire:click="deleteItem({{ $service['id'] }}, '{{ $service['type'] }}')"
                            spinner class="btn-ghost text-error btn-sm" />
                        @endscope
                        <x-slot:empty>
                            <x-icon name="o-cube" label="Hizmet ekleyin veya paket ekleyin." />
                        </x-slot:empty>
                    </x-table>
                    <x:slot:menu>
                        <livewire:admin.actions.client_offer.add_service :branch_ids="$branch" />
                        <livewire:admin.actions.client_offer.add_package :branch_ids="$branch" />
                    </x:slot:menu>
                    <x:slot:actions>
                        Toplam : {{ LiveHelper::price_text($this->totalPrice()) }}
                    </x:slot:actions>
                
                </x-card>
                @endif
        </div>
        <x:slot:actions>
            @if ($this->totalPrice() > 0 || $this->price > 1)
            <x-button label="Ödeme Bölümüne Geç" icon="o-credit-card" wire:click="change_section" spinner
                class="btn-primary" />
            @else
            <x-button label="Tutar 0'dan büyük olmalı" icon="o-credit-card" disabled spinner class="btn-primary" />
            @endif
        </x:slot:actions>
    </x-card>
    @else
    <x-card title="Satış" separator shadow>
        <x:slot:menu>
            <x-button label="TOPLAM"
                badge="{{ LiveHelper::price_text($this->kalan()) }} / {{ LiveHelper::price_text($this->yapilandirilacak()) }}" />
        </x:slot:menu>
        <div class="grid lg:grid-cols-2 gap-8">
            <x-card title="Peşinat" separator shadow>
                <x-slot:menu>
                    @if ($this->kalan() > 0)
                    <livewire:admin.actions.client_sale.add_nakit :config_sale_date="$config_sale_date"
                        :branch="$branch" />
                    @endif

                </x-slot:menu>
                <x-table :headers="$headers_nakit" :rows="$pay_nakit">
                    @scope('cell_price', $nakit)
                    {{ LiveHelper::price_text($nakit['price']) }}
                    @endscope
                    @scope('actions', $nakit)
                    <x-button icon="o-trash" wire:click="delete_pay({{ $nakit['id'] }})" spinner
                        class="btn-ghost text-error btn-sm" />
                    @endscope
                    <x-slot:empty>
                        <x-icon name="o-cube" label="Peşinat ekleyin." />
                    </x-slot:empty>
                </x-table>
            </x-card>
            <x-card title="Taksit" separator shadow>
                <x-slot:menu>

                    @if ($this->taksits->count() > 0)
                    <x-button icon="o-trash" label="Taksitlendirmeyi İptal Et" spinner
                        class="btn-ghost text-error btn-sm" wire:confirm="Emin misiniz ?" wire:click="cancel_taksit" />
                    @else
                    @if ($this->kalan() > 0)
                    <livewire:admin.actions.client_sale.add_taksit :config_taksit_date="$config_taksit_date" />
                    @endif
                    @endif

                </x-slot:menu>
                <x-table :headers="$headers_taksit" :rows="$taksits">
                    @scope('cell_price', $nakit)
                    {{ LiveHelper::price_text($nakit['price']) }}
                    @endscope
                    @scope('cell_date', $taksit, $config_taksit_date)
                    <livewire:admin.actions.client_sale.change_taksit_date wire:key="taskit$taksit['id']" :config_taksit_date="$config_taksit_date"
                        :t_id="$taksit['id']" :taksit_date="$taksit['date']">
                        @endscope
                        <x-slot:empty>
                            <x-icon name="o-cube" label="Taksitlendirin." />
                        </x-slot:empty>
                </x-table>
            </x-card>
        </div>
        <x:slot:actions>
            <x-button icon="o-arrow-uturn-left" spinner label="Geri Dön" wire:click="backService"
                class="btn-ghost text-warning btn-sm"
                wire:confirm="Ödeme yapılandırması iptal edilecektir, geri dönmek istiyor musunuz ?" />
                @if ($this->kalan() > 0)
            <x-button icon="o-check" spinner label="Tutarı Yapılandırın" disabled
                 class="btn-primary btn-sm" />
                 @else
                 <x-button icon="o-check" spinner label="Satışı Tamamla"
                    class="btn-primary btn-sm" wire:click="save_sale" />
                 @endif
        </x:slot:actions>
    </x-card>
    @endif

</div>