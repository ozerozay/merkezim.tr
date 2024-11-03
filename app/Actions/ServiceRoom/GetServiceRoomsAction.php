<?php

namespace App\Actions\ServiceRoom;

use App\Models\ServiceRoom;
use Lorisleiva\Actions\Concerns\AsAction;

class GetServiceRoomsAction
{
    use AsAction;

    public function handle($branch, $category)
    {
        return ServiceRoom::query()
            ->select(['id', 'name', 'active', 'category_ids', 'branch_id'])
            ->where('active', true)
            ->when($branch, function ($query) use ($branch) {
                $query->where('branch_id', $branch);
            })
            ->when($category, function ($query) use ($category) {
                $query->whereHas('categories', function ($qc) use ($category) {
                    $qc->where('id', $category);
                });
            })
            ->with('branch:id,name')
            ->get();

    }
}
