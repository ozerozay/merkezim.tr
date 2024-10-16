<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfferItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
