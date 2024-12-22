<?php

namespace App\Livewire\Modals\Client;

use App\Actions\Spotlight\Actions\Update\DeleteSaleAction;
use App\Actions\Spotlight\Actions\Update\UpdateSaleAction;
use App\Actions\Spotlight\Actions\Update\UpdateSaleStatusAction;
use App\Models\Sale;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SaleModal extends SlideOver
{
    use Toast;

    public int|Sale $sale;

    public ?string $messageDelete = null;

    public ?string $messageEdit = null;

    public ?string $messageStatus = null;

    public $sale_staffs = [];

    public $sale_type;

    public $sale_date;

    public $expire_date;

    public $sale_status;

    public $freeze_date;

    public string $group = 'group1';

    public function mount(Sale $sale): void
    {
        $this->sale = $sale;

        $this->sale_type = $this->sale->sale_type_id;
        $this->sale_date = $this->sale->date;
        $this->sale_staffs = $this->sale->staffs;
        $this->expire_date = $this->sale->expire_date;

        $this->sale_status = $this->sale->status;
    }

    public function changeStatus(): void
    {
        $validator = \Validator::make([
            'id' => $this->sale->id,
            'message' => $this->messageStatus,
            'status' => $this->sale_status,
            'freeze_date' => $this->freeze_date,
        ], [
            'id' => 'required',
            'message' => 'required',
            'status' => 'required',
            'freeze_date' => 'required_if:status,freeze',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        UpdateSaleStatusAction::run($validator->validated());

        $this->dispatch('refresh-client-sales');
        $this->success('Durum düzenlendi.');
        $this->close();
    }

    public function edit(): void
    {
        $validator = \Validator::make([
            'id' => $this->sale->id,
            'message' => $this->messageEdit,
            'sale_type_id' => $this->sale_type,
            'expire_date' => $this->expire_date,
            'date' => $this->sale_date,
            'staffs' => $this->sale_staffs,
        ], [
            'id' => 'required',
            'message' => 'required',
            'sale_type_id' => 'required',
            'expire_date' => 'nullable|after:today',
            'date' => 'required',
            'staffs' => 'nullable',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        UpdateSaleAction::run($validator->validated());

        $this->dispatch('refresh-client-sales');
        $this->success('Satış düzenlendi.');
        $this->close();
    }

    public function delete(): void
    {
        $validator = \Validator::make([
            'id' => $this->sale->id,
            'message' => $this->messageDelete,
        ], [
            'id' => 'required|exists:sale',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        DeleteSaleAction::run($validator->validated());

        $this->dispatch('refresh-client-sales');
        $this->success('Satış silindi.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.sale-modal');
    }
}
