<?php

namespace App\Livewire\Settings\Defination\Room;

use App\Models\ServiceRoom;
use App\Traits\StrHelper;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class RoomEdit extends SlideOver
{
    use StrHelper, Toast;

    public int|ServiceRoom $room;

    public ?string $name = null;

    public ?bool $active = null;

    public ?int $branch_id = null;

    public array $category_ids = [];

    public function mount(ServiceRoom $room)
    {
        $this->room = $room;
        $this->fill($room);
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
            'active' => $this->active,
            'category_ids' => $this->category_ids,
        ], [
            'name' => ['required', Rule::unique('service_rooms', 'name')->where('branch_id', $this->branch_id)->ignore($this->room->id)],
            'active' => ['required', 'boolean'],
            'category_ids' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->room->update($validator->validated());

        $this->success('Oda düzenlendi.');
        $this->close(andDispatch: ['defination-room-update']);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.room.room-edit');
    }
}
