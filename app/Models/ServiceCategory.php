<?php

namespace App\Models;

use App\Traits\BranchActive;
use App\Traits\StringHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

/**
 * 
 *
 * @property int $id
 * @property array $branch_ids
 * @property string $name
 * @property bool $active
 * @property float $price
 * @property int $earn
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Coupon> $coupons
 * @property-read int|null $coupons_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Branch[] $branches
 * @property-read int|null $branches_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory active(?bool $active)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory branch(string $column = 'branch_id')
 * @method static \Database\Factories\ServiceCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereBranchIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereEarn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory withoutTrashed()
 * @mixin \Eloquent
 */
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
