<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        ];
    }

    public function clientServices()
    {
        return $this->hasMany(ClientService::class);
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function offerItem()
    {
        return $this->morphOne(OfferItem::class, 'offer_itemable');
    }
}
