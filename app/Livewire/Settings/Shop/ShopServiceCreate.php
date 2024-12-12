<?php

namespace App\Livewire\Settings\Shop;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Actions\Spotlight\Actions\Get\GetServices;
use App\Models\ShopService;
use App\Rules\PriceValidation;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ShopServiceCreate extends SlideOver
{
    use StrHelper, Toast;

    public $branch_id;

    public ?string $name = null;

    public $service_id = null;

    public $description;

    public $discount_text;

    public $price;

    public $buy_max;

    public $buy_min;

    public $kdv;

    public function mount()
    {
        $this->branch_id = auth()->user()->staff_branch()->first()?->id ?? null;
    }

    public function updatedBranchId($value)
    {
        $this->service_id = null;
    }

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function save()
    {
        $validator = \Validator::make([
            'name' => $this->strUpper($this->name),
            'unique_id' => CreateUniqueID::run('shop_service'),
            'branch_id' => $this->branch_id,
            'service_id' => $this->service_id,
            'description' => $this->description,
            'discount_text' => $this->discount_text,
            'price' => $this->price,
            'buy_max' => $this->buy_max,
            'buy_min' => $this->buy_min,
            'kdv' => $this->kdv,
        ], [
            'name' => ['required', Rule::unique('shop_services', 'name')->where('branch_id', $this->branch_id)],
            'unique_id' => 'required',
            'branch_id' => ['required', 'exists:branches,id'],
            'service_id' => ['required', 'exists:services,id'],
            'description' => 'required',
            'discount_text' => 'nullable',
            'price' => ['required', new PriceValidation, 'min:1'],
            'buy_max' => ['nullable', 'integer'],
            'buy_min' => ['nullable', 'integer'],
            'kdv' => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        if (ShopService::where('service_id', $this->service_id)->exists()) {
            $this->error('Hizmet bulunuyor.');

            return;
        }

        ShopService::create($validator->validated());

        $this->success('Hizmet oluşturuldu.');
        $this->close(andDispatch: ['settings-shop-service-update']);
    }

    public function getServices()
    {
        if (! $this->branch_id) {
            return [];
        } else {
            return GetServices::run([$this->branch_id], null, null, true);
        }
    }

    public function render()
    {
        return view('livewire.spotlight.settings.shop.shop-service-create', [
            'services' => $this->getServices(),
        ]);
    }
}
