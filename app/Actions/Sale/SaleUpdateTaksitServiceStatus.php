<?php

namespace App\Actions\Sale;

use App\Models\ClientService;
use App\Models\ClientTaksit;
use App\Models\Sale;
use Lorisleiva\Actions\Concerns\AsAction;

class SaleUpdateTaksitServiceStatus
{
    use AsAction;

    public function handle($sale)
    {
        try {

            $sale = Sale::select('id', $sale)->first();

            if ($sale) {
                $client_taksit = ClientTaksit::query()
                    ->where('sale_id', $sale->id)
                    ->update([
                        'status' => $sale->status,
                    ]);
                $client_taksit = ClientService::query()
                    ->where('sale_id', $sale->id)
                    ->update([
                        'status' => $sale->status,
                    ]);
            }

        } catch (\Throwable $e) {

        }
    }
}
