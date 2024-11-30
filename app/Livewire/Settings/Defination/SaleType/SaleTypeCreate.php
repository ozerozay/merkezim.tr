<?php

namespace App\Livewire\Settings\Defination\SaleType;

use App\Models\SaleType;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SaleTypeCreate extends SlideOver
{
    use StrHelper, Toast;

    public ?string $name = null;

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
            'active' => true,
        ], [
            'name' => ['required', Rule::unique('sale_types', 'name')],
            'active' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        SaleType::create($validator->validated());

        $this->success('Satış tipi oluşturuldu.');
        $this->close(andDispatch: ['defination-sale-type-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.sale-type.sale-type-create');
    }
}
