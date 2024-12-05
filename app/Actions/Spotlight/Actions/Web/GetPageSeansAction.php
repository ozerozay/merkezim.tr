<?php

namespace App\Actions\Spotlight\Actions\Web;

use App\Models\ClientService;
use App\SaleStatus;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetPageSeansAction
{
    use AsAction;

    public function handle($show_category, $show_zero)
    {
        try {
            if ($show_category) {
                return ClientService::selectRaw('id, client_id, service_id,status ,SUM(remaining) as remaining, SUM(total) as total,(SUM(remaining) / NULLIF(SUM(total), 0)) * 100 as remaining_percentage')
                    ->where('client_id', auth()->user()->id)
                    ->where('status', SaleStatus::success)
                    ->when(! $show_zero, function ($q) {
                        $q->where('remaining', '>', 0);
                    })
                    ->whereRelation('service', 'visible', '=', true)
                    ->whereRelation('service', 'active', '=', true)
                    ->with('service:name,id,category_id', 'service.category:id,name', 'sale:id,unique_id')
                    ->groupBy('service_id')
                    ->get();
            } else {
                return ClientService::query()
                    ->where('client_id', auth()->user()->id)
                    ->where('status', SaleStatus::success)
                    ->whereRelation('service', 'visible', '=', true)
                    ->whereRelation('service', 'active', '=', true)
                    ->when(! $show_zero, function ($q) {
                        $q->where('remaining', '>', 0);
                    })
                    ->with('service:id,name,category_id', 'service.category:id,name', 'sale:id,unique_id')
                    ->get();
            }

        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
            //return collect();
        }
    }
}
