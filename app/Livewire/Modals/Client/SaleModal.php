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

    // Form alanları
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

    // Validasyon kuralları
    protected array $editRules = [
        'messageEdit' => 'required',
        'sale_type' => 'required',
        'expire_date' => 'nullable|after:today',
        'sale_date' => 'required',
        'sale_staffs' => 'nullable',
    ];

    protected array $statusRules = [
        'messageStatus' => 'required',
        'sale_status' => 'required',
        'freeze_date' => 'required_if:sale_status,freeze',
    ];

    protected array $deleteRules = [
        'messageDelete' => 'required',
    ];

    public function mount(Sale $sale): void
    {
        $this->sale = $sale;
        $this->initializeFormData();
    }

    protected function initializeFormData(): void
    {
        $this->sale_type = $this->sale->sale_type_id;
        $this->sale_date = $this->sale->date;
        $this->sale_staffs = $this->sale->staffs;
        $this->expire_date = $this->sale->expire_date;
        $this->sale_status = $this->sale->status;
    }

    public function changeStatus(): void
    {
        try {
            $data = $this->validate($this->statusRules);

            UpdateSaleStatusAction::run([
                'id' => $this->sale->id,
                'message' => $this->messageStatus,
                'status' => $this->sale_status,
                'freeze_date' => $this->freeze_date,
            ]);

            $this->handleSuccess('Durum düzenlendi.');
        } catch (\Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    public function edit(): void
    {
        try {
            $data = $this->validate($this->editRules);

            UpdateSaleAction::run([
                'id' => $this->sale->id,
                'message' => $this->messageEdit,
                'sale_type_id' => $this->sale_type,
                'expire_date' => $this->expire_date,
                'date' => $this->sale_date,
                'staffs' => $this->sale_staffs,
            ]);

            $this->handleSuccess('Satış düzenlendi.');
        } catch (\Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    public function delete(): void
    {
        try {
            $data = $this->validate($this->deleteRules);

            DeleteSaleAction::run([
                'id' => $this->sale->id,
                'message' => $this->messageDelete,
            ]);

            $this->handleSuccess('Satış silindi.');
        } catch (\Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    protected function handleSuccess(string $message): void
    {
        $this->dispatch('refresh-client-sales');
        $this->success($message);
        $this->close();
    }

    protected function handleError(string $message): void
    {
        $this->error($message);
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.sale-modal');
    }
}
