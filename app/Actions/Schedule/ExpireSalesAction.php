<?php

namespace App\Actions\Schedule;

use App\Models\Sale;
use App\SaleStatus;
use App\Tenant;
use Lorisleiva\Actions\Concerns\AsAction;

class ExpireSalesAction
{
    use AsAction;

    public function handle()
    {
        try {

            Tenant::all()->each(function ($tenant) {
                $tenant->run(function () {
                    $sales = Sale::query()
                        ->select('id', 'status', 'expire_date', 'status')
                        ->where('status', SaleStatus::active)
                        ->where(function ($q) {
                            $q->whereNotNull('expire_date')->where('expire_date', '<', date('Y-m-d'));
                        })->update([
                            'status' => SaleStatus::expired,
                        ]);
                });
            });

        } catch (\Throwable $e) {

        }
    }
}
