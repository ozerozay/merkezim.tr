<?php

namespace App\Actions\Spotlight\Actions\Get;

use App\Models\Service;
use Lorisleiva\Actions\Concerns\AsAction;

class GetServices
{
    use AsAction;

    public function handle($branch_ids, $gender, $search, $withoutShop = false)
    {
        return Service::query()
            ->where('active', true)
            ->when($branch_ids, function ($q) use ($branch_ids) {
                $q->whereHas('category.branches', function ($q) use ($branch_ids) {
                    $q->whereIn('id', $branch_ids);
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            })
            ->when($gender, function ($q) use ($gender) {
                $q->whereIn('gender', [$gender, 0]);
            })
            ->whereHas('category', function ($q) {
                $q->where('active', true);
            })
            ->when($withoutShop, function ($q) {
                $q->whereDoesntHave('shopService');
            })
            ->with('category.branches')
            ->orderBy('name')
            ->get();
    }
}
