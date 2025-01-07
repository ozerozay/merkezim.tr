<?php

namespace App\Actions\Spotlight\Actions\Client\Get;

use App\Models\ServiceCategory;
use App\SaleStatus;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientServiceCategory
{
    use AsAction;

    public function handle(array $data)
    {
        return ServiceCategory::query()
            ->whereHas('services', function ($query) use ($data) {
                $query->whereHas('clientServices', function ($q) use ($data) {
                    $q->where('client_id', $data['client_id'])
                        ->where('remaining', '>', 0)
                        ->where('status', SaleStatus::success);
                });
            })
            ->where('active', true)
            ->orderBy('name')
            ->get();
    }
}
