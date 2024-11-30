<?php

namespace App\Livewire\Settings\Defination\Label;

use App\Models\Label;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class LabelCreate extends SlideOver
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
            'name' => ['required', Rule::unique('labels', 'name')],
            'active' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        Label::create($validator->validated());

        $this->success('Etiket oluşturuldu.');
        $this->close(andDispatch: ['defination-label-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.label.label-create');
    }
}
