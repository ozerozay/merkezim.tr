<?php

namespace App\Livewire\Reports\Filter;

use App\Actions\Label\GetActiveLabels;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ClientReportFilter extends SlideOver
{
    use Toast;

    public $select_sale_id = null;

    public $select_service_id = null;

    public $select_taksit_id = null;

    public $select_appointment_id = null;

    public $select_product_id = null;

    public $select_label_id = [];

    public $select_referans_id = null;

    public $select_gender_id = null;

    public $select_offer_id = null;

    public $select_adisyon_id = null;

    public $select_first_login_id = null;

    public $branches = [];

    public $select_sale = [
        [
            'id' => 1,
            'name' => 'Aktif satışı bulunanlar',
        ],
        [
            'id' => 2,
            'name' => 'Aktif ikinci üyeliği bulunmayanlar',
        ],
        [
            'id' => 3,
            'name' => 'Aktif satışı bulunmayanlar',
        ],
        [
            'id' => 4,
            'name' => 'İki ve üzeri aktif satışı bulunanlar',
        ],
    ];

    public $select_adisyon = [
        [
            'id' => 1,
            'name' => 'Adisyonu bulunanlar',
        ],
        [
            'id' => 2,
            'name' => 'Adisyonu bulunmayanlar',
        ],
    ];

    public $select_service = [
        [
            'id' => 1,
            'name' => 'Hizmeti devam edenler',
        ],
        [
            'id' => 2,
            'name' => 'Hizmeti bitmiş olanlar',
        ],
    ];

    public $select_taksit = [
        [
            'id' => 1,
            'name' => 'Ödemeleri bitmiş olanlar',
        ],
        [
            'id' => 2,
            'name' => 'Gecikmiş ödemesi bulunanlar',
        ],
        [
            'id' => 3,
            'name' => 'Ödemesi bulunanlar',
        ],
    ];

    public $select_appointment = [
        [
            'id' => 1,
            'name' => 'Aktif randevusu bulunanlar',
        ],
        [
            'id' => 2,
            'name' => 'Randevusu bulunmayanlar',
        ],
    ];

    public $select_product = [
        [
            'id' => 1,
            'name' => 'Ürün satışı bulunanlar',
        ],
        [
            'id' => 2,
            'name' => 'Ürün satışı bulunmayanlar',
        ],
    ];

    public $select_referans = [
        [
            'id' => 1,
            'name' => 'Referansı bulunanlar',
        ],
        [
            'id' => 2,
            'name' => 'Referansı bulunmayanlar',
        ],
    ];

    public $select_gender = [
        [
            'id' => 1,
            'name' => 'Kadın',
        ],
        [
            'id' => 2,
            'name' => 'Erkek',
        ],
    ];

    public $select_offer = [
        [
            'id' => 1,
            'name' => 'Teklifi bulunanlar',
        ],
        [
            'id' => 2,
            'name' => 'Teklifi bulunmayanlar',
        ],
    ];

    public $select_first_login = [
        [
            'id' => 1,
            'name' => 'Sisteme giriş yapanlar',
        ],
        [
            'id' => 2,
            'name' => 'Sisteme giriş yapmayanlar',
        ],
    ];

    public $labels;

    public function mount($filters)
    {
        $this->labels = GetActiveLabels::run();
        $this->fill($filters);
    }

    public function save()
    {
        $filters = [
            'select_sale_id' => $this->select_sale_id,
            'select_service_id' => $this->select_service_id,
            'select_taksit_id' => $this->select_taksit_id,
            'select_appointment_id' => $this->select_appointment_id,
            'select_referans_id' => $this->select_referans_id,
            'branches' => $this->branches,
            'select_product_id' => $this->select_product_id,
            'select_label_id' => $this->select_label_id,
            'select_gender_id' => $this->select_gender_id,
            'select_offer_id' => $this->select_offer_id,
            'select_adisyon_id' => $this->select_adisyon_id,
            'select_first_login_id' => $this->select_first_login_id,
        ];

        $this->dispatch('report-client-filter', $filters);
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.reports.filter.client-report-filter', [
            'select_sale' => $this->select_sale,
            'select_service' => $this->select_service,
            'select_taksit' => $this->select_taksit,
            'select_appointment' => $this->select_appointment,
            'select_product' => $this->select_product,
            'select_label' => $this->labels,
            'select_referans' => $this->select_referans,
            'select_gender' => $this->select_gender,
            'select_offer' => $this->select_offer,
            'select_adisyon' => $this->select_adisyon,
            'select_first_login' => $this->select_first_login,
        ]);
    }
}
