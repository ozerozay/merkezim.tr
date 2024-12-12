<?php

namespace App\Livewire\Settings\Shop;

use App\Models\ShopPackage;
use App\Rules\PriceValidation;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ShopPackageSettingsEdit extends SlideOver
{
    use StrHelper, Toast;

    public int|ShopPackage $package;

    public ?string $name = null;

    public $description;

    public $discount_text;

    public $price;

    public $buy_max;

    public $month;

    public $kdv;

    public $active = true;

    public function mount(ShopPackage $package)
    {
        $this->package = $package;
        $this->fill($package);
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

    public function save(): void
    {

        $validator = \Validator::make([
            'name' => $this->strUpper($this->name),
            'description' => $this->description,
            'discount_text' => $this->discount_text,
            'price' => $this->price,
            'buy_max' => $this->buy_max,
            'month' => $this->month,
            'kdv' => $this->kdv,
            'active' => $this->active,
        ], [
            'name' => ['required', Rule::unique('shop_packages', 'name')->ignore($this->package->id)],
            'description' => 'required',
            'discount_text' => 'nullable',
            'price' => ['required', new PriceValidation, 'min:1'],
            'buy_max' => ['nullable', 'integer'],
            'month' => ['nullable', 'integer'],
            'kdv' => ['nullable', 'integer'],
            'active' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->package->update($validator->validated());

        $this->success('Paket düzenlendi.');
        $this->close(andDispatch: ['settings-shop-package-update']);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.shop.shop-package-settings-edit');
    }
}
