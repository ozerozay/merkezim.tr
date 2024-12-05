<?php

namespace App\Livewire\Settings\Defination\SMSTemplate;

use App\Models\SMSTemplate;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SMSTemplateDefination extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'defination-sms-update' => '$refresh',
    ];

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function templates()
    {
        return SMSTemplate::query()
            ->whereIn('branch_id', auth()->user()->staff_branches)
            ->orderBy('branch_id')
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->with('branch:id,name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.s-m-s-template.s-m-s-template-defination', [
            'templates' => $this->templates(),
        ]);
    }
}
