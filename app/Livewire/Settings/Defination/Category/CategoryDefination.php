<?php

namespace App\Livewire\Settings\Defination\Category;

use App\Models\ServiceCategory;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CategoryDefination extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'defination-category-update' => '$refresh',
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

    public function categories()
    {
        return ServiceCategory::query()
            ->whereHas('branches', function ($q) {
                $q->whereIn('id', auth()->user()->staff_branches);
            })
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->with('branches:id,name')
            ->paginate(10);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.category.category-defination', [
            'categories' => $this->categories(),
        ]);
    }
}
