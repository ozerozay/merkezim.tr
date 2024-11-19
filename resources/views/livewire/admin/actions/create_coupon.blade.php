<?php

use App\Actions\Coupon\CreateCouponAction;
use App\Actions\Helper\CreateCouponCode;
use App\Actions\User\CheckClientBranchAction;
use App\Actions\User\CheckUserInstantApprove;
use App\Actions\User\CreateApproveRequestAction;
use App\ApproveTypes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Kupon Oluştur')]
class extends Component
{
    use Toast;

    #[Url(as: 'client')]
    public $client_id = null;

    public $code;

    public $discount_type = true;

    public $count = 1;

    public $discount_amount = 1;

    public $end_date;

    public $min_order;

    public $discount_types;

    public $config_end_date = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    public function mount()
    {
        $this->discount_types = [
            ['id' => true, 'name' => 'YÜZDE'],
            ['id' => false, 'name' => 'TL'],
        ];
        $this->code = CreateCouponCode::run();
        $this->config_end_date['minDate'] = Carbon::now()->addDay(1)->format('Y-m-d');

    }

    public function generate_code()
    {
        $this->code = CreateCouponCode::run();
    }

    public function save()
    {

        $validator = Validator::make([
            'client_id' => $this->client_id,
            'code' => $this->code,
            'discount_type' => $this->discount_type,
            'count' => $this->count,
            'discount_amount' => $this->discount_amount,
            'end_date' => $this->end_date,
            'min_order' => $this->min_order,
            'user_id' => auth()->user()->id,
        ], [
            'client_id' => 'required|exists:users,id',
            'code' => 'required|unique:coupons,code',
            'discount_type' => 'required|boolean',
            'count' => 'required|integer',
            'discount_amount' => 'required|min:1',
            'end_date' => 'nullable|before:today',
            'min_order' => 'nullable|min:1',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CheckClientBranchAction::run($this->client_id);

        if (CheckUserInstantApprove::run(auth()->user()->id)) {
            CreateCouponAction::run($validator->validated());

            $this->success('Kupon oluşturuldu.');
        } else {
            CreateApproveRequestAction::run($validator->validated(), auth()->user()->id, ApproveTypes::create_coupon, $this->message);

            $this->success(\App\Peren::$approve_request_ok);
        }

    }
};
?>
<div>
    <x-header title="Kupon Oluştur" separator progress-indicator="save" />
    <x-form wire:submit="save">
        <livewire:components.form.client_dropdown wire:model="client_id" />
        <x-input label="Kupon Kodu" wire:model="code">
            <x-slot:append>
                <x-button label="Rastgele Oluştur" wire:click="generate_code" icon="o-check"
                    class="btn-primary rounded-s-none" />
            </x-slot:append>
        </x-input>
        <div class="flex">
            <div class="w-1/2">
                <x-radio label="İndirim Çeşidi" :options="$discount_types" wire:model.live="discount_type" />
            </div>
            <div class="w-1/2">
                @if ($discount_type)
                <livewire:components.form.number_dropdown wire:model="discount_amount" suffix="%" max="100"
                    label="İndirim Yüzdesi" :includeZero="false" />
                @else
                <x-input label="İndirim Tutarı (TL)" wire:model="discount_amount" suffix="₺" money />
                @endif
            </div>
        </div>
        <livewire:components.form.number_dropdown wire:model="count" suffix="Adet" max="100" label="Kupon Adedi"
            :includeZero="false" />
        <x-input label="Minimum Sipariş Tutarı (TL) - 0 Sınır Yok" wire:model="min_order" suffix="₺" money />
        <x-datepicker label="Son Geçerlilik Tarihi" wire:model="end_date" icon="o-calendar"
                    :config="$config_end_date" />
        <x-slot:actions>
            <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
