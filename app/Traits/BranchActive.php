<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BranchActive
{
    public function scopeActive(Builder $query, ?bool $active): void
    {
        if ($active) {
            $query->where('active', $active);
        }
    }

    public function scopeBranch(Builder $query, string $column = 'branch_id'): void
    {
        $query->whereIn('id', auth()->user()->staff_branches);
    }
}
