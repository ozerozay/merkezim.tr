<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsTemplateBranch extends Model
{
    protected $guarded = ['id'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public static function getTemplateContent(string $type, int $branchId): string
    {
        $template = self::where('type', $type)
            ->where('branch_id', $branchId)
            ->where('active', true)
            ->first();

        return $template?->content ?? '';
    }
}
