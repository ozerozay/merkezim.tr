<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int $package_id
 * @property int $service_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Package $package
 * @property-read \App\Models\Service $service
 * @method static \Database\Factories\PackageItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PackageItem withoutTrashed()
 * @mixin \Eloquent
 */
class PackageItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
