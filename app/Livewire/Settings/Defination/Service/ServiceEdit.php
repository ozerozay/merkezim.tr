<?php

namespace App\Livewire\Settings\Defination\Service;

use App\Models\Service;
use App\Rules\PriceValidation;
use Illuminate\Validation\Rule;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ServiceEdit extends SlideOver
{
    use \App\Traits\StrHelper, \Mary\Traits\Toast;

    public int|Service $service;

    public $name;

    public $gender = 1;

    public $seans = 0;

    public $duration = 10;

    public $price = 1;

    public $min_day = 0;

    public $visible = true;

    public $active = true;

    public function mount(Service $service)
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

    public function save(): void
    {
        $validator = \Validator::make([
            'name' => $this->strUpper($this->name),
            'gender' => $this->gender,
            'duration' => $this->duration,
            'price' => $this->price,
            'seans' => $this->seans,
            'min_day' => $this->min_day,
            'is_visible' => $this->visible,
            'active' => $this->active,
        ], [
            'name' => ['required', Rule::unique('services', 'name')->where('category_id', $this->service->category_id)->ignore($this->service->id)],
            'gender' => 'required',
            'duration' => 'required',
            'seans' => 'required',
            'price' => ['required', new PriceValidation],
            'min_day' => 'required',
            'is_visible' => 'required',
            'active' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->service->update($validator->validated());

        $this->success('Hizmet düzenlendi.');
        $this->close(andDispatch: ['defination-service-update']);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.service.service-edit');
    }
}
