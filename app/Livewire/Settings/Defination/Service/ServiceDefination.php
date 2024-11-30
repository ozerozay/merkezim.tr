<?php

namespace App\Livewire\Settings\Defination\Service;

use App\Models\Service;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ServiceDefination extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'defination-service-update' => '$refresh',
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

    public function services()
    {
        return Service::query()
            ->whereHas('category', function ($q) {
                $q->whereHas('branches', function ($qv) {
                    $qv->whereIn('id', auth()->user()->staff_branches);
                });
            })
            ->orderBy('category_id')
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->with('category:id,name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.service.service-defination', [
            'services' => $this->services(),
        ]);
    }
}
