<?php

namespace App\Actions\Service;

use App\Models\Service;
use Lorisleiva\Actions\Concerns\AsAction;

class GetServicesAction
{
    use AsAction;

    public function handle($branch_ids, $gender)
    {
        return Service::query()
            ->where('active', true)
            ->when($branch_ids, function ($q) use ($branch_ids) {
                $q->whereHas('category.branches', function ($q) use ($branch_ids) {
                    $q->whereIn('id', $branch_ids);
                });
            })
            ->when($gender, function ($q) use ($gender) {
                $q->whereIn('gender', [$gender, 0]);
            })
            ->whereHas('category', function ($q) {
                $q->where('active', true);
            })
            ->with('category.branches')
            ->orderBy('name')
            ->get();
    }
}
