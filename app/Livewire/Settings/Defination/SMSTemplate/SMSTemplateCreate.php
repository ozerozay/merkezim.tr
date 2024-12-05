<?php

namespace App\Livewire\Settings\Defination\SMSTemplate;

use App\Models\SMSTemplate;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SMSTemplateCreate extends SlideOver
{
    use StrHelper, Toast;

    public ?string $name = null;

    public ?string $message = null;

    public $branch_id;

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
            'message' => $this->message,
            'branch_id' => $this->branch_id,
        ], [
            'branch_id' => 'required|exists:branches,id',
            'message' => 'required',
            'name' => ['required', Rule::unique('s_m_s_templates', 'name')->where('branch_id', $this->branch_id)],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        SMSTemplate::create($validator->validated());

        $this->success('Şablon oluşturuldu.');
        $this->close(andDispatch: ['defination-sms-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.s-m-s-template.s-m-s-template-create');
    }
}
