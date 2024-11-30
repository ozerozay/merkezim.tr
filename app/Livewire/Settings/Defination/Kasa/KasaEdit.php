<?php

namespace App\Livewire\Settings\Defination\Kasa;

use App\Models\Kasa;
use Illuminate\Validation\Rule;
use WireElements\Pro\Components\SlideOver\SlideOver;

class KasaEdit extends SlideOver
{
    use \App\Traits\StrHelper, \Mary\Traits\Toast;

    public int|Kasa $kasa;

    public ?string $name = null;

    public ?bool $active = null;

    public $branch_id;

    public function mount(Kasa $kasa)
    {
        $this->kasa = $kasa;
        $this->fill($kasa);
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
            'name' => ['required', Rule::unique('kasas', 'name')->where('branch_id', $this->branch_id)->ignore($this->kasa->id)],
            'active' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->kasa->update($validator->validated());

        $this->success('Kasa düzenlendi.');
        $this->close(andDispatch: ['defination-kasa-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.kasa.kasa-edit');
    }
}
