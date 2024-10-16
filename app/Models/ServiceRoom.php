<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

/**
 * 
 *
 * @property int $id
 * @property int $branch_id
 * @property array $category_ids
 * @property string $name
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch $branch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ServiceCategory[] $categories
 * @property-read int|null $categories_count
 * @method static \Database\Factories\ServiceRoomFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom whereCategoryIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRoom withoutTrashed()
 * @mixin \Eloquent
 */
class ServiceRoom extends Model
{
    use HasFactory, HasJsonRelationships, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'category_ids',
        'name',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'category_ids' => 'json',
            'active' => 'boolean',
        ];
    }

    public function categories()
    {
        return $this->belongsToJson(ServiceCategory::class, 'category_ids');
    }

    public function category_names()
    {
        return $this->categories()->pluck('name')->implode(', ');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
