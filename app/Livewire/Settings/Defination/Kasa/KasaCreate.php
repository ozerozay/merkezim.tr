<?php

namespace App\Livewire\Settings\Defination\Kasa;

use App\Models\Kasa;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class KasaCreate extends SlideOver
{
    use StrHelper, Toast;

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
            'name' => ['required', Rule::unique('kasas', 'name')->where('branch_id', $this->branch_id)],
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        Kasa::create($validator->validated());

        $this->success('Kasa oluşturuldu.');
        $this->close(andDispatch: ['defination-kasa-update']);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.kasa.kasa-create');
    }
}
