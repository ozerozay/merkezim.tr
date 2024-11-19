<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'gender' => 'integer',
            'seans' => 'integer',
            'duration' => 'integer',
            'min_day' => 'integer',
            'active' => 'boolean',
            'price' => 'float',
            'visible' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function clientServices()
    {
        return $this->hasMany(ClientService::class);
    }

    public function offerItem()
    {
        return $this->morphOne(OfferItem::class, 'itemable');
    }

    public function adisyonServices()
    {
        return $this->hasMany(AdisyonService::class);
    }

    public function clientTaksitsLocks()
    {
        return $this->hasMany(ClientTaksitsLock::class);
    }
}
