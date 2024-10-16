<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function staffs()
    {
        return $this->belongsToJson(Branch::class, 'staffs');
    }
}
