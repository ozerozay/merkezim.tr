<?php

use App\Actions\Offer\UpdateOfferAction;
use App\Actions\User\CheckUserInstantApprove;
use App\Actions\User\CreateApproveRequestAction;
use App\ApproveTypes;
use App\Peren;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    #[Modelable]
    public $offer = null;

    public bool $show = false;

    public $expire_date = null;

    public $price = null;

    public $message = null;

    public $month = 0;

    public $config_expire_date = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    public function mount()
    {
        $this->config_expire_date['minDate'] = Carbon::now()->addDay(1)->format('Y-m-d');
        $this->fill($this->offer);
    }

    public function save()
    {
        $validator = Validator::make([
            'offer_id' => $this->offer->id,
            'price' => $this->price,
            'expire_date' => $this->expire_date,
            'message' => $this->message,
            'month' => $this->month,
        ], [
            'offer_id' => 'required|exists:offers,id',
            'price' => 'required|decimal:0,2|min:1',
            'expire_date' => 'nullable|after:today',
            'message' => 'required',
            'month' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        if (CheckUserInstantApprove::run(auth()->user()->id)) {
            UpdateOfferAction::run($validator->validated());

            $this->success('Teklif düzenlendi.');
        } else {
            CreateApproveRequestAction::run($validator->validated(), auth()->user()->id, ApproveTypes::update_offer, $this->message);

            $this->success(Peren::$approve_request_ok);
        }
        $this->show = false;
    }
};
?>
<div>
    <x-button icon="o-pencil" tooltip="Düzenle" @click="$wire.show = true" spinner class="btn-sm text-blue-600" />
    @if ($offer)
    <template x-teleport="body">
    <x-modal wire:model="show" progress-indicator title="Teklifi Düzenle">
        <x-form wire:submit='save'>
            <x-input label="Tutar" wire:model="price" suffix="₺" money />
            <x-datepicker label="Son Geçerlilik Tarihi (Sınırsız ise boş bırakın)" wire:model="expire_date"
                icon="o-calendar" :config="$config_expire_date" />
            <livewire:components.form.number_dropdown label="Paket Kullanım Süresi (Sınırsız ise 0)"
                includeZero="true" suffix="Ay" wire:model="month" />
            <x-input label="Açıklama" wire:model="message" />
            <x-slot:actions>
                <x-button label="İptal" @click="$wire.show = false" />
                <x-button label="Gönder" type="submit" spinner="save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
    </template>
    @endif
</div>