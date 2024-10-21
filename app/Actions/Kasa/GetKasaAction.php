<?php

namespace App\Actions\Kasa;

use App\Models\Kasa;
use Lorisleiva\Actions\Concerns\AsAction;

class GetKasaAction
{
    use AsAction;

    public function handle($branch_ids)
    {
        return Kasa::query()
            ->where('active', true)
            ->when($branch_ids, function ($q) use ($branch_ids) {
                $q->whereIn('branch_id', $branch_ids);
            })
            ->with('branch')
            ->orderBy('name')
            ->get();
    }
}
