<?php

namespace App\Models;

use Carbon\Carbon;
use App\OfferStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['expire_date'];

    protected function casts(): array
    {
        return [
            'status' => 'App\OfferStatus',
        ];
    }

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

    public function clientServices()
    {
        return $this->hasMany(ClientService::class);
    }

    protected function dateHumanCreated(): Attribute
    {
        return Attribute::make(
            get: fn(?Carbon $value) => $this->created_at->format('d/m/Y')
        );
    }

    protected function dateHumanExpire(): Attribute
    {
        return Attribute::make(
            get: fn(?Carbon $value) => $this->expire_date == null
                ? 'SÜRESİZ'
                : Carbon::parse($this->expire_date)->format('d/m/Y')
        );
    }
}
