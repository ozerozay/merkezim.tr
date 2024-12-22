<?php

namespace App\Actions\Spotlight\Actions\Client\Get;

use App\Models\ServiceCategory;
use App\SaleStatus;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientServiceCategory
{
    use AsAction;

    public function handle($client, $branch = null)
    {
        return ServiceCategory::query()
            ->select(['id', 'name', 'active', 'branch_ids'])
            ->where('active', true)
            ->whereHas('services', function ($q) use ($client) {
                $q->whereHas('clientServices', function ($qc) use ($client) {
                    $qc->where('client_id', $client)
                        ->where('status', SaleStatus::success)
                        ->where('remaining', '>', 0);
                });
            })
            ->when($branch != null, function ($q) use ($branch) {
                $q->whereHas('branches', function ($q) use ($branch) {
                    $q->where('id', $branch);
                });
            })
            ->get();
    }
}
