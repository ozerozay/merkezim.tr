<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Package extends Model
{
    use HasFactory;
    use HasJsonRelationships;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            //'branch_ids' => 'json',
            'gender' => 'integer',
            'active' => 'boolean',
            'buy_time' => 'integer',
            'price' => 'float',
        ];
    }

    public function items()
    {
        return $this->hasMany(PackageItem::class, 'package_id');
    }

    public function offerItem()
    {
        return $this->morphOne(OfferItem::class, 'itemable');
    }

    public function clientServices()
    {
        return $this->hasMany(ClientService::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
