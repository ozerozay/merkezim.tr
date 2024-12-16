<?php

namespace App\Livewire\Reports\Filter;

use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class KasaReportFilter extends SlideOver
{
    use Toast;

    public $branches = [];

    public $date_range;

    public $select_type_id = [];

    public $select_kasa_id = [];

    public $select_masraf_id = [];

    public $select_client_id = [];

    public $select_create_staff_id = [];

    public $select_payment_id = null;

    public $select_payment = [
        [
            'id' => 1,
            'name' => 'Giriş',
        ],
        [
            'id' => 2,
            'name' => 'Çıkış',
        ],
    ];

    public function mount($filters)
    {
        $this->fill($filters);
    }

    public function save()
    {
        $filters = [
            'date_range' => $this->date_range,
            'branches' => $this->branches,
            'select_type_id' => $this->select_type_id,
            'select_kasa_id' => $this->select_kasa_id,
            'select_masraf_id' => $this->select_masraf_id,
            'select_client_id' => $this->select_client_id,
            'select_create_staff_id' => $this->select_create_staff_id,
            'select_payment_id' => $this->select_payment_id,
        ];

        $this->dispatch('report-filter', $filters);
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.reports.filter.kasa-report-filter');
    }
}
