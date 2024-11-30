<?php

namespace App\Livewire\Settings\Defination\Label;

use App\Models\Label;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use WireElements\Pro\Components\SlideOver\SlideOver;

class LabelDefination extends SlideOver
{
    use \Mary\Traits\Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'defination-label-update' => '$refresh',
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

    public function labels()
    {
        return Label::query()
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.label.label-defination', [
            'labels' => $this->labels(),
        ]);
    }
}
