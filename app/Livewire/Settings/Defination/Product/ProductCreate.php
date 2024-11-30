<?php

namespace App\Livewire\Settings\Defination\Product;

use App\Models\Product;
use App\Rules\PriceValidation;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ProductCreate extends SlideOver
{
    use StrHelper, Toast;

    public ?string $name = null;

    public $branch_id;

    public $price = 0.0;

    public $stok = 0;

    public function mount(): void
    {
        $this->branch_id = auth()->user()->staff_branch()->first()?->id ?? null;
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
            'branch_id' => $this->branch_id,
            'stok' => $this->stok,
            'price' => $this->price,
        ], [
            'branch_id' => 'required|exists:branches,id',
            'name' => ['required', Rule::unique('kasas', 'name')->where('branch_id', $this->branch_id)],
            'stok' => 'required|numeric|min:0',
            'price' => ['required', new PriceValidation],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        Product::create($validator->validated());

        $this->success('Ürün oluşturuldu.');
        $this->close(andDispatch: ['defination-product-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.product.product-create');
    }
}
