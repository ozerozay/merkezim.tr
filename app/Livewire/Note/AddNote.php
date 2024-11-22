<?php

namespace App\Livewire\Note;

use App\Models\User;
use Livewire\Attributes\Rule;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class AddNote extends SlideOver
{
    use Toast;

    public int|User $user;

    #[Rule('required|exists:users,id')]
    public ?int $client_id = null;

    #[Rule('required')]
    public ?string $message = null;

    public bool $showDrawerNotAl = false;

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->client_id = $user->id;
        $this->showDrawerNotAl = true;
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create_note');
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'client_id' => $this->client_id,
            'message' => $this->message,
        ], [
            'client_id' => 'required|exists:users,id',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }
    }
}
