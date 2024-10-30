<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Adisyon extends Model
{
    use HasFactory;
    use HasJsonRelationships;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['date'];

    protected function casts(): array
    {
        return [
            'staff_ids' => 'json',
            'price' => 'float',
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

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function adisyonServices()
    {
        return $this->hasMany(AdisyonService::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transacable');
    }

    public function staffs()
    {
        return $this->belongsToJson(User::class, 'staff_ids');
    }

    protected function dateHuman(): Attribute
    {
        return Attribute::make(
            get: fn (?Carbon $value) => Carbon::parse($this->date)->format('d/m/Y')
        );
    }
}
