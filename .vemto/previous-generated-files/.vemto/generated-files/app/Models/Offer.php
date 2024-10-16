<?php

namespace App\Models;

use App\OfferStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['expire_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(OfferItem::class, 'offer_id');
    }
}
