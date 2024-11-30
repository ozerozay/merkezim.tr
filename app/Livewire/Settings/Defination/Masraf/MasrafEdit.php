<?php

namespace App\Livewire\Settings\Defination\Masraf;

use App\Models\Masraf;
use Illuminate\Validation\Rule;
use WireElements\Pro\Components\SlideOver\SlideOver;

class MasrafEdit extends SlideOver
{
    use \App\Traits\StrHelper, \Mary\Traits\Toast;

    public int|Masraf $masraf;

    public ?string $name = null;

    public ?bool $active = null;

    public $branch_id;

    public function mount(Masraf $masraf)
    {
        $this->masraf = $masraf;
        $this->fill($masraf);
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
            'name' => ['required', Rule::unique('masraf', 'name')->where('branch_id', $this->branch_id)->ignore($this->masraf->id)],
            'active' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->masraf->update($validator->validated());

        $this->success('Masraf düzenlendi.');
        $this->close(andDispatch: ['defination-masraf-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.masraf.masraf-edit');
    }
}
