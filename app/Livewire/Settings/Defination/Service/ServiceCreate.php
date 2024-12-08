<?php

namespace App\Livewire\Settings\Defination\Service;

use App\Models\Service;
use App\Rules\PriceValidation;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ServiceCreate extends SlideOver
{
    use \Mary\Traits\Toast, StrHelper;

    public $name;

    public $category_id;

    public $gender = 1;

    public $seans = 0;

    public $duration = 10;

    public $price = 1;

    public $min_day = 0;

    public $visible = true;

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
            'category_id' => $this->category_id,
            'gender' => $this->gender,
            'duration' => $this->duration,
            'price' => $this->price,
            'seans' => $this->seans,
            'min_day' => $this->min_day,
            'is_visible' => $this->visible,
        ], [
            'category_id' => 'required|exists:service_categories,id',
            'name' => ['required', Rule::unique('services', 'name')->where('category_id', $this->category_id)],
            'gender' => 'required',
            'duration' => 'required',
            'seans' => 'required',
            'price' => ['required', new PriceValidation],
            'min_day' => 'required',
            'is_visible' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        Service::create($validator->validated());

        $this->success('Hizmet oluşturuldu.');
        $this->close(andDispatch: ['defination-service-update']);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.service.service-create');
    }
}
