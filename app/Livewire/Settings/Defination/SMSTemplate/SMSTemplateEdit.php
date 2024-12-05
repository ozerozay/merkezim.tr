<?php

namespace App\Livewire\Settings\Defination\SMSTemplate;

use App\Models\SMSTemplate;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SMSTemplateEdit extends SlideOver
{
    use StrHelper, Toast;

    public int|SMSTemplate $template;

    public ?bool $active = null;

    public ?string $name = null;

    public ?string $message = null;

    public $branch_id;

    public function mount(SMSTemplate $template)
    {
        $this->template = $template;
        $this->fill($template);
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
            'active' => $this->active,
        ], [
            'name' => ['required', Rule::unique('s_m_s_templates', 'name')->where('branch_id', $this->branch_id)->ignore($this->template->id)],
            'message' => ['required'],
            'active' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->template->update($validator->validated());

        $this->success('Şablon düzenlendi.');
        $this->close(andDispatch: ['defination-sms-update']);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.s-m-s-template.s-m-s-template-edit');
    }
}
