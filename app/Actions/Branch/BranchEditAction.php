<?php

namespace App\Actions\Branch;

use App\Models\Branch;
use App\Traits\StrHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class BranchEditAction
{
    use AsAction, StrHelper;

    public function handle(array $info, Branch $branch)
    {
        $info['name'] = $this->strUpper($info['name']);
        $branch->update($info);
    }
}
