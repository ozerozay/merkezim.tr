<?php

namespace App\Actions\Package;

use App\Models\Package;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPackagesAction
{
    use AsAction;

    public function handle($branch_ids, $gender)
    {
        return Package::query()
            ->where('active', true)
            ->when($branch_ids, function ($q) use ($branch_ids) {
                $q->whereHas('branches', function ($q) use ($branch_ids) {
                    $q->whereIn('id', $branch_ids);
                });
            })
            ->when($gender, function ($q) use ($gender) {
                $q->whereIn('gender', [$gender, 0]);
            })
            ->with('branches')
            ->orderBy('name')
            ->get();
    }
}
