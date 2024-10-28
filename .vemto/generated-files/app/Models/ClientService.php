<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['expire_date'];

    protected function casts(): array
    {
        return [
            'status' => 'App\SaleStatus',
            'gift' => 'boolean',
        ];
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function userServices()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clientServiceUses()
    {
        return $this->hasMany(ClientServiceUse::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    protected function dateHumanCreated(): Attribute
    {
        return Attribute::make(
            get: fn(?Carbon $value) => $this->created_at->format('d/m/Y')
        );
    }
}
