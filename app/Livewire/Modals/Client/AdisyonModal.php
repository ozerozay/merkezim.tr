<?php

namespace App\Livewire\Modals\Client;

use App\Actions\Spotlight\Actions\Update\DeleteAdisyonAction;
use App\Actions\Spotlight\Actions\Update\UpdateAdisyonAction;
use App\Models\Adisyon;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class AdisyonModal extends SlideOver
{
    use Toast;

    public int|Adisyon $adisyon;

    public $group = 'group0';

    public $messageDelete;

    public $messageEdit;

    public $adisyon_staffs = [];

    public $adisyon_date;

    public function mount(Adisyon $adisyon): void
    {
        $this->adisyon = $adisyon;

        $this->adisyon_staffs = $this->adisyon->staff_ids;
        $this->adisyon_date = $this->adisyon->date;
    }

    public function edit(): void
    {
        $validator = \Validator::make([
            'date' => $this->adisyon_date,
            'message' => $this->messageEdit,
            'staff_ids' => $this->adisyon_staffs,
            'id' => $this->adisyon->id,
        ], [
            'date' => 'required|date',
            'message' => 'required',
            'staff_ids' => 'nullable',
            'id' => 'required|exists:adisyons',
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        UpdateAdisyonAction::run($validator->validated());

        $this->dispatch('refresh-client-adisyons');
        $this->success('Adisyon düzenlendi.');
        $this->close();
    }

    public function delete(): void
    {
        $validator = \Validator::make([
            'id' => $this->adisyon->id,
            'message' => $this->messageDelete,
        ], [
            'id' => 'required|exists:adisyons',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        DeleteAdisyonAction::run($validator->validated());

        $this->dispatch('refresh-client-adisyons');
        $this->success('Adisyon silindi.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.adisyon-modal');
    }
}
