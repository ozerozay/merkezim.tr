<?php

namespace App\Actions\Schedule;

use App\Models\Sale;
use App\SaleStatus;
use App\Tenant;
use Lorisleiva\Actions\Concerns\AsAction;

class ActivateFreezedSalesAction
{
    use AsAction;

    public function handle()
    {
        try {

            Tenant::all()->each(function ($tenant) {
                $tenant->run(function () {
                    Sale::query()
                        ->select('id', 'status', 'freeze_date', 'status')
                        ->where('status', SaleStatus::freeze)
                        ->where(function ($q) {
                            $q->whereNotNull('freeze_date')->where('freeze_date', '<', date('Y-m-d'));
                        })->update([
                            'status' => SaleStatus::success,
                        ]);
                });
            });

        } catch (\Throwable $e) {

        }
    }
}
