<?php

namespace App\Livewire\Settings\Defination\Masraf;

use App\Models\Masraf;
use Illuminate\Validation\Rule;
use WireElements\Pro\Components\SlideOver\SlideOver;

class MasrafCreate extends SlideOver
{
    use \App\Traits\StrHelper, \Mary\Traits\Toast;

    public ?string $name = null;

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
            'branch_id' => $this->branch_id,
        ], [
            'branch_id' => 'required|exists:branches,id',
            'name' => ['required', Rule::unique('masraf', 'name')->where('branch_id', $this->branch_id)],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        Masraf::create($validator->validated());

        $this->success('Masraf oluşturuldu.');
        $this->close(andDispatch: ['defination-masraf-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.masraf.masraf-create');
    }
}
