<?php

use App\Actions\User\CheckClientBranchAction;
use App\Models\Offer;
use App\Models\Package;
use App\Models\Service;
use App\Models\User;
use App\OfferStatus;
use App\Peren;
use App\Traits\LiveHelper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Teklif Oluştur')]
class extends Component
{
    use Toast;

    public $client;

    public $client_id;

    public $price;

    public $branch;

    public $expire_date;

    public $month = 0;

    public $message;

    public $selected_services;

    public $config = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    protected $queryString = [
        'client',
    ];

    public function mount()
    {
        //Carbon::now();
        //LiveHelper::class
        $client_model = User::where('unique_id', $this->client)->first();

        if ($client_model) {
            try {
                CheckClientBranchAction::run($client_model->id);
                $this->client_id = $client_model->id;
                $this->branch = $client_model->branch_id;
                $this->config['minDate'] = Carbon::now()->addDays(1)->format('Y-m-d');
                $this->selected_services = collect();
            } catch (\Throwable $e) {
                $this->error('Bu danışan için yetkiniz bulunmuyor.');

                return redirect()->route('admin.actions.client_offer_customer');
            }
        } else {
            return redirect()->route('admin.actions.client_offer_customer');
        }
    }

    #[On('service-added')]
    public function service_add($info)
    {
        $services = Service::whereIn('id', $info['service_id'])->get();

        foreach ($services as $s) {
            if ($this->selected_services->count() > 0 && $this->selected_services->contains(function ($q) use ($s) {
                return $q['type'] == 'service' && $q['id'] == $s->id;
            })) {
                $this->error($s->name.' bulunuyor, değişikliği tablodan yapın.');
                break;
            }

            $this->selected_services->push([
                'id' => $s->id,
                'type' => 'service',
                'name' => $s->name,
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
            if ($this->selected_services->contains(function ($q) use ($package) {
                return $q['type'] == 'package' && $q['id'] == $package->id;
            })) {
                $this->error($package->name.' bulunuyor, değişikliği tablodan yapın.');

                return;
            }
            $this->selected_services->push([
                'id' => $package->id,
                'type' => 'package',
                'name' => $package->name,
                'quantity' => $info['quantity'],
                'price' => $package->price,
            ]);
        }
    }

    #[Computed]
    public function totalPrice()
    {
        $totalP = 0.0;
        foreach ($this->selected_services as $s) {
            $totalP += $s['price'] * $s['quantity'];
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

        collect(range(1, 50))->each(fn ($item) => $items->add(['id' => $item, 'name' => $item]));

        return $items;
    }

    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Ad'],
            ['key' => 'type', 'label' => 'Çeşit'],
            ['key' => 'quantity', 'label' => 'Adet'],
        ];
    }

    public function with(): array
    {
        return [
            'selected_services' => $this->selected_services,
            'headers' => $this->headers(),
            'quantities' => $this->quantities(),
        ];
    }

    public function reset_form()
    {
        $this->price = 0;
        $this->expire_date = null;
        $this->month = 0;
    }

    public function save_offer()
    {
        try {
            $this->validate([
                'client_id' => 'required|exists:users,id',
                'price' => 'required|decimal:0,2|min:1',
                'expire_date' => 'nullable|after:today',
                'month' => 'required',
                'message' => 'required',
            ], [], [
                'price' => 'Tutar',
                'expire_date' => 'Geçerlilik tarihi',
                'month' => 'Kullanım süresi',
                'message' => 'Mesaj',
            ]);

            if ($this->selected_services->count() == 0) {
                $this->error('Hizmet eklemelisiniz.');

                return;
            }

            DB::beginTransaction();

            $offer = Offer::create([
                'unique_id' => Peren::unique_offer_code(),
                'user_id' => Auth::user()->id,
                'client_id' => $this->client_id,
                'expire_date' => Peren::parseDate($this->expire_date),
                'price' => $this->price,
                'status' => OfferStatus::waiting,
                'message' => $this->message,
            ]);

            foreach ($this->selected_services as $s) {
                if ($s['type'] == 'service') {
                    $service = Service::find($s['id']);
                    $service->offerItem()->create([
                        'offer_id' => $offer->id,
                        'quantity' => $s['quantity'],
                    ]);
                } else {
                    $package = Package::find($s['id']);
                    $package->offerItem()->create([
                        'offer_id' => $offer->id,
                        'quantity' => $s['quantity'],
                    ]);
                }

            }

            DB::commit();

            $this->selected_services = collect();
            $this->reset('price', 'message', 'expire_date', 'month');
            $this->success('Teklif oluşturuldu.');

        } catch (\Throwable $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                $this->error($e->getMessage());

                return;
            }
            $this->error($e->getMessage());
        }

    }
};

?>
<div>
    <x-card title="Teklif Oluştur" separator shadow>
        <div class="grid lg:grid-cols-2 gap-8">
           
            <x-card title="Hizmetler" separator progress-indicator shadow>
                <x-table :headers="$headers" :rows="$selected_services">
                    @scope('cell_quantity', $item, $quantities)
                    <x-select wire:model.number="selected_services.{{ $loop->index }}.quantity" :options="$quantities"
                        wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)" class="select-sm !w-20" />
                    @endscope
                    @scope('cell_type', $service)
                    {{ $service['type'] == 'package' ? 'Paket' : 'Hizmet' }}
                    @endscope
                    @scope('actions', $service)
                    <x-button icon="o-trash" wire:click="deleteItem({{ $service['id'] }}, '{{ $service['type'] }}')" spinner
                        class="btn-ghost text-error btn-sm" />
                    @endscope
                    <x-slot:empty>
                        <x-icon name="o-cube" label="Hizmet ekleyin veya paket ekleyin." />
                    </x-slot:empty>
                </x-table>
                <x:slot:menu>
                    <livewire:admin.actions.client_offer.add_service :branch="$branch" />
                    <livewire:admin.actions.client_offer.add_package :branch="$branch" />
                </x:slot:menu>
                <x:slot:actions>
                    Toplam : {{ LiveHelper::price_text($this->totalPrice()) }}
                </x:slot:actions>
            </x-card>
            <x-form wire:submit="save_offer">
                <x-card title="Teklif Bilgileri" separator shadow>

                    <x-input label="Tutar" wire:model="price" suffix="₺" money />
                    <x-datepicker label="Son Geçerlilik Tarihi (Sınırsız ise boş bırakın)" wire:model="expire_date" icon="o-calendar" :config="$config" />
                    <livewire:components.form.number_dropdown label="Paket Kullanım Süresi (Sınırsız ise 0)"
                        includeZero="true" suffix="Ay" wire:model="month" />
                    <x-input label="Açıklama" wire:model="message" />

                    <x-slot:menu>

                        <x-button icon="o-arrow-path" label="Sıfırla" wire:click="reset_form"
                            class="btn-sm btn-warning" />
                    </x-slot:menu>
                    <x-slot:actions>
                        <x-button label="{{ $selected_services->count() > 0 ? 'TEKLİFİ OLUŞTUR' : 'HİZMET SEÇİN' }}"
                            class="btn-success" spinner="save_offer" type="submit"
                            x-bind:disabled="{{ $selected_services->count() > 0 ? false : true }}" />
                    </x-slot:actions>
                </x-card>
            </x-form>
        </div>

    </x-card>

</div>