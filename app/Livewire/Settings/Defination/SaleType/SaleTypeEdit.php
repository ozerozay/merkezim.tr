<?php

namespace App\Livewire\Settings\Defination\SaleType;

use App\Models\SaleType;
use Illuminate\Validation\Rule;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SaleTypeEdit extends SlideOver
{
    use \App\Traits\StrHelper, \Mary\Traits\Toast;

    public int|SaleType $type;

    public ?string $name = null;

    public ?bool $active = null;

    public function mount(SaleType $type)
    {
        $this->type = $type;
        $this->fill($type);
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
        ], [
            'name' => ['required', Rule::unique('sale_types', 'name')->ignore($this->type->id)],
            'active' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->type->update($validator->validated());

        $this->success('Satış tipi düzenlendi.');
        $this->close(andDispatch: ['defination-sale-type-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.sale-type.sale-type-edit');
    }
}
