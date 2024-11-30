<?php

namespace App\Livewire\Settings\Defination\Package;

use App\Models\Package;
use App\Rules\PriceValidation;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class PackageCreate extends SlideOver
{
    use StrHelper, Toast;

    public $name;

    public $branch_id;

    public $gender = 1;

    public $price = 0;

    public $buy_time = 0;

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
            'gender' => $this->gender,
            'buy_time' => $this->buy_time,
            'price' => $this->price,
        ], [
            'branch_id' => 'required|exists:branches,id',
            'name' => ['required', Rule::unique('packages', 'name')->where('branch_id', $this->branch_id)],
            'gender' => 'required',
            'buy_time' => 'required',
            'price' => ['required', new PriceValidation],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        Package::create($validator->validated());

        $this->success('Paket oluşturuldu, hizmet eklemek için düzenleyin.');
        $this->close(andDispatch: ['defination-package-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.package.package-create');
    }
}
