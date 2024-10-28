<?php

namespace App\Actions\Schedule;

use App\Models\Offer;
use App\OfferStatus;
use App\Tenant;
use Lorisleiva\Actions\Concerns\AsAction;

class OfferMakeCancelAction
{
    use AsAction;

    public function handle()
    {
        try {

            Tenant::all()->each(function ($tenant) {
                $tenant->run(function () {
                    Offer::query()
                        ->select('id', 'expire_date', 'status')
                        ->where('status', OfferStatus::waiting)
                        ->where(function ($q) {
                            $q->whereNotNull('expire_date')->where('expire_date', '<', date('Y-m-d'));
                        })->update([
                            'status' => OfferStatus::cancel,
                        ]);
                });
            });

        } catch (\Throwable $e) {

        }
    }
}
