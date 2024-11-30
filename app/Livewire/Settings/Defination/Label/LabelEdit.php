<?php

namespace App\Livewire\Settings\Defination\Label;

use App\Models\Label;
use Illuminate\Validation\Rule;
use WireElements\Pro\Components\SlideOver\SlideOver;

class LabelEdit extends SlideOver
{
    use \App\Traits\StrHelper, \Mary\Traits\Toast;

    public int|Label $label;

    public ?string $name = null;

    public ?bool $active = null;

    public function mount(Label $label)
    {
        $this->label = $label;
        $this->fill($label);
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
            'name' => ['required', Rule::unique('labels', 'name')->ignore($this->label->id)],
            'active' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->label->update($validator->validated());

        $this->success('Etiket düzenlendi.');
        $this->close(andDispatch: ['defination-label-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.label.label-edit');
    }
}
