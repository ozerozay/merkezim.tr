<?php

namespace App\Models;

use App\Traits\BranchActive;
use App\Traits\StringHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class ServiceCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BranchActive;
    use HasJsonRelationships;

    protected $fillable = ['branch_ids', 'name', 'active', 'price', 'earn'];

    protected function casts(): array
    {
        return [
            'branch_ids' => 'json',
            'price' => 'float',
            'active' => 'boolean',
            'earn' => 'integer',
        ];
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'category_id');
    }

    protected static function booted(): void
    {
        static::creating(function (ServiceCategory $category) {
            $category->name = StringHelper::strUpper($category->name);
        });

        static::updating(function (ServiceCategory $category) {
            $category->name = StringHelper::strUpper($category->name);
        });
    }

    public function category_staff_active()
    {
        return $this->query()
            ->where('active', true)
            ->whereHas('branches', function ($q) {
                $q->whereIn('id', Auth::user()->staff_branches);
            })
            ->orderBy('name');
    }

    public function branch_names()
    {
        return $this->branches()
            ->pluck('name')
            ->implode(', ');
    }

    public function branches()
    {
        return $this->belongsToJson(Branch::class, 'branch_ids');
    }
}
