<?php

namespace App\Actions\Spotlight\Actions\Get;

use App\Models\Package;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPackages
{
    use AsAction;

    public function handle($branch_ids, $gender, $search = null)
    {
        return Package::query()
            ->where('active', true)
            ->when($branch_ids, function ($q) use ($branch_ids) {
                $q->whereIn('branch_id', $branch_ids);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            })
            ->when($gender, function ($q) use ($gender) {
                $q->whereIn('gender', [$gender, 0]);
            })
            ->with('branch:id,name')
            ->orderBy('name')
            ->get();
    }
}
