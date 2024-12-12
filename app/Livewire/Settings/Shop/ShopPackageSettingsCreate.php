<?php

namespace App\Livewire\Settings\Shop;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Actions\Spotlight\Actions\Get\GetPackages;
use App\Models\ShopPackage;
use App\Rules\PriceValidation;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ShopPackageSettingsCreate extends SlideOver
{
    use StrHelper, Toast;

    public $branch_id;

    public ?string $name = null;

    public $package_id = null;

    public $description;

    public $discount_text;

    public $price;

    public $buy_max;

    public $month;

    public $kdv;

    public function mount()
    {
        $this->branch_id = auth()->user()->staff_branch()->first()?->id ?? null;
    }

    public function updatedBranchId($value)
    {
        $this->package_id = null;
    }

    public function save()
    {

        $validator = \Validator::make([
            'name' => $this->strUpper($this->name),
            'unique_id' => CreateUniqueID::run('shop_package'),
            'branch_id' => $this->branch_id,
            'package_id' => $this->package_id,
            'description' => $this->description,
            'discount_text' => $this->discount_text,
            'price' => $this->price,
            'buy_max' => $this->buy_max,
            'month' => $this->month,
            'kdv' => $this->kdv,
        ], [
            'name' => ['required', Rule::unique('shop_packages', 'name')],
            'unique_id' => 'required',
            'branch_id' => ['required', 'exists:branches,id'],
            'package_id' => ['required', 'exists:packages,id'],
            'description' => 'required',
            'discount_text' => 'nullable',
            'price' => ['required', new PriceValidation, 'min:1'],
            'buy_max' => ['nullable', 'integer'],
            'month' => ['nullable', 'integer'],
            'kdv' => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        if (ShopPackage::where('package_id', $this->package_id)->exists()) {
            $this->error('Paket bulunuyor.');

            return;
        }

        ShopPackage::create($validator->validated());

        $this->success('Paket oluşturuldu.');
        $this->close(andDispatch: ['settings-shop-package-update']);
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

    public function getPackages()
    {
        if (! $this->branch_id) {
            return [];
        } else {
            return GetPackages::run([$this->branch_id], null, null, true);
        }
    }

    public function render()
    {
        return view('livewire.spotlight.settings.shop.shop-package-settings-create', [
            'packages' => $this->getPackages(),
        ]);
    }
}
