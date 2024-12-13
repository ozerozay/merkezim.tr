<?php

namespace App\Livewire\Modals\Talep;

use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateTalepModal extends SlideOver
{
    use Toast;

    public ?string $name = null;

    public ?string $phone = null;

    public ?string $message = null;

    public ?string $type = \App\TalepType::diger->name;

    public ?int $branch_id = null;

    public function mount()
    {
        $this->branch_id = auth()->user()->staff_branch()->first()?->id ?? null;
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'branch_id' => $this->branch_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'type' => $this->type,
            'message' => $this->message,
            'date' => date('Y-m-d'),
            'user_id' => auth()->user()->id,
        ], [
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required',
            'phone' => 'required|unique:taleps,phone|unique:users,phone',
            'type' => 'required',
            'message' => 'required',
            'date' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        \App\Models\Talep::create($validator->validated());

        $this->show = false;
        $this->dispatch('refresh-taleps');
        $this->success('Talep oluşturuldu.');
    }

    public function render()
    {
        return view('livewire.spotlight.modals.talep.create-talep-modal');
    }
}
