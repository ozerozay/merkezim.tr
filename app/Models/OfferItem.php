<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function itemable()
    {
        return $this->morphTo();
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
