<?php

namespace App\Models;

use Carbon\Carbon;
use App\Observers\SaleObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasJsonRelationships;

    protected $table = 'sale';

    protected $guarded = ['id'];

    protected $dates = ['date', 'expire_date'];

    protected function casts(): array
    {
        return [
            'staffs' => 'json',
            'status' => 'App\SaleStatus',
            'coupons' => 'json',
            'visible' => 'boolean',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function saleType()
    {
        return $this->belongsTo(SaleType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clientServices()
    {
        return $this->hasMany(ClientService::class);
    }

    public function clientTaksits()
    {
        return $this->hasMany(ClientTaksit::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transacable');
    }

    public function getDateAttribute($v)
    {
        return Carbon::parse($v)->format('d/m/Y');
    }

    public function staffs()
    {
        return $this->belongsToJson(Branch::class, 'staffs');
    }

    public function coupons()
    {
        return $this->belongsToJson(Coupon::class, 'coupons');
    }

    protected function dateHumanCreated(): Attribute
    {
        return Attribute::make(
            get: fn(?Carbon $value) => $this->created_at->format('d/m/Y')
        );
    }
}
