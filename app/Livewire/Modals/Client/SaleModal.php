<?php

namespace App\Livewire\Modals\Client;

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

    public function render()
    {
        return view('livewire.spotlight.modals.client.sale-modal');
    }
}
