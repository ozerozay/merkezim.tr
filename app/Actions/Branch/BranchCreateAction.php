<?php

namespace App\Actions\Branch;

use App\Models\Branch;
use App\Traits\StrHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class BranchCreateAction
{
    use AsAction, StrHelper;

    public function handle(array $info)
    {
        $info['name'] = $this->strUpper($info['name']);

        return Branch::create($info);
    }
}
