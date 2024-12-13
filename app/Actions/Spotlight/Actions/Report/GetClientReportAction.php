<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\AppointmentStatus;
use App\Models\User;
use App\OfferStatus;
use App\SaleStatus;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {
            return User::query()
                ->where('active', true)
                ->doesntHave('roles')
                ->when(array_key_exists('branches', $filters) && ! $filters['branches'] == null, function (Builder $q) use ($filters) {
                    //dump($filters['branches']);
                    $q->whereIn('branch_id', $filters['branches']);
                })
                ->when(! (array_key_exists('branches', $filters) && ! $filters['branches'] == null), function (Builder $q) {
                    $q->whereIn('branch_id', auth()->user()->staff_branches);
                })
                ->when(array_key_exists('select_gender_id', $filters) && ! $filters['select_gender_id'] == null, function (Builder $q) use ($filters) {
                    $q->where('gender', $filters['select_gender_id']);
                })
                ->when(array_key_exists('select_sale_id', $filters) && ! $filters['select_sale_id'] == null, function (Builder $q) use ($filters) {
                    switch ($filters['select_sale_id']) {
                        case 1:
                            $q->whereHas('sales', function ($qa) {
                                $qa->where('status', SaleStatus::success);
                            });
                            break;
                        case 2:
                            $q->whereHas('sales', function ($qa) {
                                $qa->where('status', SaleStatus::success);
                            }, '<', 2);
                            break;
                        case 3:
                            $q->whereDoesntHave('sales', function ($qa) {
                                $qa->where('status', SaleStatus::success);
                            });
                            break;
                        case 4:
                            $q->whereHas('sales', function ($qa) {
                                $qa->where('status', SaleStatus::success);
                            }, '>', 1);
                            break;
                    }
                })
                ->when(array_key_exists('select_adisyon_id', $filters) && ! $filters['select_adisyon_id'] == null, function (Builder $q) use ($filters) {
                    switch ($filters['select_adisyon_id']) {
                        case 1:
                            $q->whereHas('clientAdisyons', function () {}, '>', 0);
                            break;
                        case 2:
                            $q->whereHas('clientAdisyons', function () {}, '==', 0);
                            break;
                    }
                })
                ->when(array_key_exists('select_service_id', $filters) && ! $filters['select_service_id'] == null, function (Builder $q) use ($filters) {
                    switch ($filters['select_service_id']) {
                        case 1:
                            $q->whereHas('clientServices', function ($qa) {
                                $qa->where('status', SaleStatus::success)->where('remaining', '>', 0);
                            });
                            break;
                        case 2:
                            $q->whereHas('clientServices', function ($qa) {
                                $qa->where('status', SaleStatus::success)->whereNot('remaining', '>', 0);
                            });
                            break;
                    }
                })
                ->when(array_key_exists('select_taksit_id', $filters) && ! $filters['select_taksit_id'] == null, function (Builder $q) use ($filters) {
                    switch ($filters['select_taksit_id']) {
                        case 1:
                            $q->whereDoesntHave('clientTaksits', function ($qa) {
                                $qa->where('status', SaleStatus::success)->where('remaining', '>', 0);
                            });
                            break;
                        case 2:
                            $q->whereHas('clientTaksits', function ($qa) {
                                $qa->where('status', SaleStatus::success)->where('remaining', '>', 0)->whereDate('date', '<=', date('Y-m-d'));
                            });
                            break;
                        case 3:
                            $q->whereHas('clientTaksits', function ($qa) {
                                $qa->where('status', SaleStatus::success)->where('remaining', '>', 0);
                            });
                            break;
                    }
                })
                ->when(array_key_exists('select_appointment_id', $filters) && ! $filters['select_appointment_id'] == null, function (Builder $q) use ($filters) {
                    switch ($filters['select_appointment_id']) {
                        case 1:
                            $q->whereHas('clientAppointments', function ($qa) {
                                $qa->whereIn('status', AppointmentStatus::activeNoLate()->toArray());
                            });
                            break;
                        case 2:
                            $q->whereDoesntHave('clientAppointments', function ($qa) {
                                $qa->whereIn('status', AppointmentStatus::activeNoLate()->toArray());
                            });
                            break;
                    }
                })
                ->when(array_key_exists('select_product_id', $filters) && ! $filters['select_product_id'] == null, function (Builder $q) use ($filters) {
                    switch ($filters['select_product_id']) {
                        case 1:
                            $q->whereHas('clientSaleProducts');
                            break;
                        case 2:
                            $q->whereDoesntHave('clientSaleProducts');
                            break;
                    }
                })
                ->when(array_key_exists('select_referans_id', $filters) && ! $filters['select_referans_id'] == null, function (Builder $q) use ($filters) {
                    switch ($filters['select_referans_id']) {
                        case 1:
                            $q->whereHas('clientReferans');
                            break;
                        case 2:
                            $q->whereDoesntHave('clientReferans');
                            break;
                    }
                })
                ->when(array_key_exists('select_offer_id', $filters) && ! $filters['select_offer_id'] == null, function (Builder $q) use ($filters) {
                    switch ($filters['select_offer_id']) {
                        case 1:
                            $q->whereHas('clientOffers', function ($qa) {
                                $qa->where('status', OfferStatus::success);
                            });
                            break;
                        case 2:
                            $q->whereDoesntHave('clientOffers', function ($qa) {
                                $qa->where('status', OfferStatus::success);
                            });
                            break;
                    }
                })
                ->when(array_key_exists('select_label_id', $filters) && ! $filters['select_label_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereHas('client_labels', function ($qa) use ($filters) {
                        $qa->whereIn('id', $filters['select_label_id']);
                    });
                })
                ->when(array_key_exists('select_first_login_id', $filters) && ! $filters['select_first_login_id'] == null, function (Builder $q) use ($filters) {
                    $q->where('first_login', $filters['select_first_login_id'] == 1);
                })
                ->orderBy(...array_values($sortBy))
                ->withSum([
                    'clientTaksits as taksits_late_remaining' => function ($q) {
                        $q->whereDate('date', '<=', date('Y-m-d'))
                            ->where('status', SaleStatus::success);
                    },
                ], 'remaining')
                ->withSum([
                    'clientTaksits as taksits_remaining' => function ($q) {
                        $q->where('status', SaleStatus::success);
                    },
                ], 'remaining')
                ->withSum([
                    'client_sales as total_sale' => function ($q) {
                        $q->where('status', SaleStatus::success);
                    },
                ], 'price')
                ->withCount(['clientAppointments', 'clientAppointments as active_appointment' => function ($q) {
                    $q->whereIn('status', AppointmentStatus::activeNoLate());
                }])
                ->withCount(['clientOffers', 'clientOffers as active_offer' => function ($q) {
                    $q->where('status', OfferStatus::success);
                }])

                ->withCount(['clientReferans', 'clientReferans as total_referans'])
                ->with('client_branch:id,name', 'client_labels:id,name')
                ->paginate(10);

        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);
        }
    }
}
