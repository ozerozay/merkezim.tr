<?php

namespace App\Livewire\Settings\Shop;

use App\Models\ShopService;
use App\Rules\PriceValidation;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ShopServiceEdit extends SlideOver
{
    use StrHelper, Toast;

    public int|ShopService $service;

    public ?string $name = null;

    public $description;

    public $discount_text;

    public $price;

    public $buy_max;

    public $buy_min;

    public $kdv;

    public $active;

    public function mount(ShopService $service)
    {
        $this->service = $service;
        $this->fill($service);
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
            'description' => $this->description,
            'discount_text' => $this->discount_text,
            'price' => $this->price,
            'buy_max' => $this->buy_max,
            'buy_min' => $this->buy_min,
            'kdv' => $this->kdv,
            'active' => $this->active,
        ], [
            'name' => ['required', Rule::unique('shop_services', 'name')->where('branch_id', $this->service->branch_id)->ignore($this->service->id)],
            'description' => 'required',
            'discount_text' => 'nullable',
            'price' => ['required', new PriceValidation, 'min:1'],
            'buy_max' => ['nullable', 'integer'],
            'buy_min' => ['nullable', 'integer'],
            'kdv' => ['nullable', 'integer'],
            'active' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->service->update($validator->validated());

        $this->success('Hizmet düzenlendi.');
        $this->close(andDispatch: ['settings-shop-service-update']);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.shop.shop-service-edit');
    }
}
