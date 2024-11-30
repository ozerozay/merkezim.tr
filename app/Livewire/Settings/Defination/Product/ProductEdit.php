<?php

namespace App\Livewire\Settings\Defination\Product;

use App\Models\Product;
use App\Rules\PriceValidation;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ProductEdit extends SlideOver
{
    use StrHelper, Toast;

    public int|Product $product;

    public ?string $name = null;

    public $price = 0.0;

    public ?bool $active = null;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->fill($product);
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
            'active' => $this->active,
            'price' => $this->price,
        ], [
            'name' => ['required', Rule::unique('products', 'name')->where('branch_id', $this->product->branch_id)->ignore($this->product->id)],
            'active' => ['required', 'boolean'],
            'price' => ['required', new PriceValidation],

        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->product->update($validator->validated());

        $this->success('Ürün düzenlendi.');
        $this->close(andDispatch: ['defination-product-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.product.product-edit');
    }
}
