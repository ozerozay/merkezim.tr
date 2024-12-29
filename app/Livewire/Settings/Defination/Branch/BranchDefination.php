<?php

namespace App\Livewire\Settings\Defination\Branch;

use App\AppointmentStatus;
use App\Models\Settings;
use Livewire\Attributes\Locked;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class BranchDefination extends SlideOver
{
    use Toast;

    public $branch_id;

    public $client_page_earn = false;

    public $client_page_offer = false;

    public $client_page_seans = false;

    public $client_page_coupon = false;

    public $client_page_fatura = false;

    public $client_page_taksit = false;

    public $client_page_appointment = false;

    public $client_page_package = false;

    public $client_page_support = false;

    public $client_page_referans = false;

    public $client_page_taksit_pay = false;

    public $client_page_offer_request = false;

    public $client_page_appointment_show_services = false;

    public $client_page_appointment_create_once_category = false;

    public $client_page_appointment_create_appointment_approve = false;

    public $client_page_appointment_create_appointment_late_payment = false;

    public $client_page_seans_add_seans = false;

    public $client_page_seans_show_zero = false;

    public $client_page_seans_show_category = false;

    public $client_page_taksit_show_zero = false;

    public $client_page_taksit_show_locked = false;

    public $payment_taksit_include_komisyon = 0;

    public $payment_tip_include_komisyon = 0;

    public $payment_offer_include_komisyon = 0;

    public $client_payment_types = [];

    public $payment_taksit_include_kdv = 0;

    public $payment_tip_include_kdv = 0;

    public $payment_offer_include_kdv = 0;

    public $client_page_appointment_cancel_time = 0;

    public $client_page_shop_include_kdv = 0;

    public $client_page_appointment_show = [];

    public $client_page_appointment_create = [];

    public $client_page_appointment_create_branches = [];

    #[Locked]
    public $payment_types = [
        [
            'id' => 'havale',
            'name' => 'Havale',
        ],
        [
            'id' => 'kk',
            'name' => 'KK',
        ],
    ];

    public $appointment_statuses = [];

    #[Locked]
    public $appointment_create_methods = [
        [
            'id' => 'manuel',
            'name' => 'Belirli Bir Tarih Seçerek',
        ],
        [
            'id' => 'range',
            'name' => 'Tarih Aralığı Seçerek',
        ],
        [
            'id' => 'multi',
            'name' => 'Birden Fazla Tarih Seçerek',
        ],
    ];

    public $appointment_create_branches = [];

    public function mount(): void
    {
        try {
            $this->branch_id = 1;

            $settings = Settings::query()
                ->where('branch_id', $this->branch_id)
                ->first();

            if ($settings) {
                $data = $settings->data;

                $this->fill($data);
            }

            foreach (AppointmentStatus::cases() as $status) {
                $this->appointment_statuses[] = [
                    'id' => $status->name,
                    'name' => $status->label(),
                ];
            }

            foreach (auth()->user()->staff_branch()->where('active', true)->get() as $branch) {
                $this->appointment_create_branches[] = [
                    'id' => $branch->id,
                    'name' => $branch->name,
                ];
            }

        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.');
            $this->close();
        }

    }

    public function save(): void
    {
        try {
            $validator = \Validator::make(
                [
                    'client_page_earn' => $this->client_page_earn,
                    'client_page_offer' => $this->client_page_offer,
                    'client_page_seans' => $this->client_page_seans,
                    'client_page_coupon' => $this->client_page_coupon,
                    'client_page_fatura' => $this->client_page_fatura,
                    'client_page_taksit' => $this->client_page_taksit,
                    'client_page_appointment' => $this->client_page_appointment,
                    'client_page_package' => $this->client_page_package,
                    'client_page_support' => $this->client_page_support,
                    'client_page_referans' => $this->client_page_referans,
                    'client_page_taksit_pay' => $this->client_page_taksit_pay,
                    'client_page_offer_request' => $this->client_page_offer_request,
                    'client_page_appointment_show_services' => $this->client_page_appointment_show_services,
                    'client_page_appointment_create_once_category' => $this->client_page_appointment_create_once_category,
                    'client_page_appointment_create_appointment_approve' => $this->client_page_appointment_create_appointment_approve,
                    'client_page_appointment_create_appointment_late_payment' => $this->client_page_appointment_create_appointment_late_payment,
                    'client_page_seans_add_seans' => $this->client_page_seans_add_seans,
                    'client_page_seans_show_zero' => $this->client_page_seans_show_zero,
                    'client_page_seans_show_category' => $this->client_page_seans_show_category,
                    'client_page_taksit_show_zero' => $this->client_page_taksit_show_zero,
                    'client_page_taksit_show_locked' => $this->client_page_taksit_show_locked,
                    'payment_taksit_include_komisyon' => $this->payment_taksit_include_komisyon,
                    'payment_offer_include_komisyon' => $this->payment_offer_include_komisyon,
                    'payment_tip_include_komisyon' => $this->payment_tip_include_komisyon,
                    'client_payment_types' => $this->client_payment_types,
                    'payment_taksit_include_kdv' => $this->payment_taksit_include_kdv,
                    'payment_tip_include_kdv' => $this->payment_tip_include_kdv,
                    'payment_offer_include_kdv' => $this->payment_offer_include_kdv,
                    'client_page_appointment_show' => $this->client_page_appointment_show,
                    'client_page_appointment_create' => $this->client_page_appointment_create,
                    'client_page_appointment_create_branches' => $this->client_page_appointment_create_branches,
                    'client_page_shop_include_kdv' => $this->client_page_shop_include_kdv,
                    'client_page_appointment_cancel_time' => $this->client_page_appointment_cancel_time,
                ],
                [
                    // Boolean validation
                    'client_page_earn' => 'boolean',
                    'client_page_offer' => 'boolean',
                    'client_page_seans' => 'boolean',
                    'client_page_coupon' => 'boolean',
                    'client_page_fatura' => 'boolean',
                    'client_page_taksit' => 'boolean',
                    'client_page_appointment' => 'boolean',
                    'client_page_package' => 'boolean',
                    'client_page_support' => 'boolean',
                    'client_page_referans' => 'boolean',
                    'client_page_taksit_pay' => 'boolean',
                    'client_page_offer_request' => 'boolean',
                    'client_page_appointment_show_services' => 'boolean',
                    'client_page_appointment_create_once_category' => 'boolean',
                    'client_page_appointment_create_appointment_approve' => 'boolean',
                    'client_page_appointment_create_appointment_late_payment' => 'boolean',
                    'client_page_seans_add_seans' => 'boolean',
                    'client_page_seans_show_zero' => 'boolean',
                    'client_page_seans_show_category' => 'boolean',
                    'client_page_taksit_show_zero' => 'boolean',
                    'client_page_taksit_show_locked' => 'boolean',

                    // Array validation
                    'client_payment_types' => 'array',
                    'client_payment_types.*' => 'in:havale,kk', // Assuming only 'havale' and 'kk' are valid

                    'client_page_appointment_show' => 'array',
                    'client_page_appointment_show.*' => 'in:awaiting_approve,waiting,confirmed,rejected,cancel,merkez,late,forwarded,finish,teyitli',

                    'client_page_appointment_create' => 'array',
                    'client_page_appointment_create.*' => 'in:manuel,range,multi',

                    'client_page_appointment_create_branches' => 'array',
                    'client_page_appointment_create_branches.*' => 'integer', // Assuming integer values are expected for branches

                    // Payment validation
                    'payment_taksit_include_kdv' => 'numeric|min:0|max:99', // Valid KDV rates
                    'payment_tip_include_kdv' => 'numeric|min:0|max:99', // Valid KDV rates
                    'payment_offer_include_kdv' => 'numeric|min:0|max:99', // Valid KDV rates
                    'client_page_shop_include_kdv' => 'numeric|min:0|max:99', // Valid KDV rates
                    'payment_tip_include_komisyon' => 'decimal:0,2|min:0|max:99',
                    'payment_taksit_include_komisyon' => 'decimal:0,2|min:0|max:99',
                    'payment_offer_include_komisyon' => 'decimal:0,2|min:0|max:99',

                    'client_page_appointment_cancel_time' => 'numeric|min:0',
                ]
            );

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            Settings::query()
                ->where('branch_id', $this->branch_id)
                ->update(['data' => $validator->validated()]);

            cache()->forget('tenant.'.tenant()->id.'.settings'.$this->branch_id);

            \Cache::rememberForever('tenant.'.tenant()->id.'.settings'.$this->branch_id, function () {
                return \App\Models\Settings::where('branch_id', 1)->first()->toArray()['data'];
            });

            dump(cache()->get('tenant.'.tenant()->id.'.settings'.$this->branch_id));

        } catch (\Throwable $e) {
            dump($e->getMessage());
            $this->error('Lütfen tekrar deneyin.'.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.branch.branch-defination');
    }
}
