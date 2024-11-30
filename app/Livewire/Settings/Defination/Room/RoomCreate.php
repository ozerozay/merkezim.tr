<?php

namespace App\Livewire\Settings\Defination\Room;

use App\Models\ServiceRoom;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class RoomCreate extends SlideOver
{
    use StrHelper, Toast;

    public ?string $name = null;

    public ?int $branch_id = null;

    public array $category_ids = [];

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
            'category_ids' => $this->category_ids,
        ], [
            'name' => ['required', Rule::unique('service_rooms', 'name')->where('branch_id', $this->branch_id)],
            'branch_id' => 'required|exists:branches,id',
            'category_ids' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        ServiceRoom::create($validator->validate());

        $this->success('Oda oluşturuldu.');
        $this->close(andDispatch: ['defination-room-update']);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.room.room-create');
    }
}
