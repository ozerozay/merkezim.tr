<?php

namespace App\Livewire\Settings\Defination\Package;

use App\Models\Package;
use App\Rules\PriceValidation;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use WireElements\Pro\Components\SlideOver\SlideOver;

class PackageEdit extends SlideOver
{
    use \App\Traits\StrHelper, \Mary\Traits\Toast;

    public int|Package $package;

    public $name;

    public $gender = 1;

    public $price = 0;

    public $buy_time = 0;

    public function mount(Package $package)
    {
        $this->package = $package->load('items.service:id,name');
        $this->fill($package);
    }

    #[On('modal-package-service-added')]
    public function addService($info): void
    {
        $services = \App\Models\Service::whereIn('id', $info['service_ids'])->get();

        foreach ($services as $s) {
            $this->package->items()->create([
                'service_id' => $s->id,
                'quantity' => $info['quantity'],
                'gift' => $info['gift'],
            ]);
        }

        $this->dispatch('defination-package-update');
    }

    public function deleteItem($id)
    {
        $this->package->items()->where('id', $id)->delete();

        $this->dispatch('defination-package-update');
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
            'gender' => $this->gender,
            'buy_time' => $this->buy_time,
            'price' => $this->price,
        ], [
            'name' => ['required', Rule::unique('packages', 'name')->where('branch_id', $this->package->branch_id)->ignore($this->package->id)],
            'active' => ['required', 'boolean'],
            'gender' => 'required',
            'buy_time' => 'required',
            'price' => ['required', new PriceValidation],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->package->update($validator->validated());

        $this->success('Paket düzenlendi.');
        $this->close(andDispatch: ['defination-package-update']);
    }

    public function render()
    {

        return view('livewire.spotlight.settings.defination.package.package-edit');
    }
}
