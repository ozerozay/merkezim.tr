<?php

namespace App\Livewire\Settings\Defination\Category;

use App\Models\ServiceCategory;
use App\Traits\StrHelper;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CategoryCreate extends SlideOver
{
    use StrHelper, Toast;

    public ?string $name = null;

    public int $earn = 0;

    public array $branch_ids = [];

    public function mount(): void
    {
        $this->branch_ids = [auth()->user()->staff_branch()->first()?->id] ?? [];
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
            'branch_ids' => $this->branch_ids,
            'earn' => $this->earn,
            'price' => 0,
        ], [
            'branch_ids' => 'required',
            'name' => ['required'],
            'earn' => 'required|integer',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        ServiceCategory::create($validator->validated());

        $this->success('Kategori oluşturuldu.');
        $this->close(andDispatch: ['defination-category-update']);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.category.category-create');
    }
}
