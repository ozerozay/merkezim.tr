<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'created_at' => 'date:Y-m-d H:i:s',
            'end_date' => 'date:Y-m-d',
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

    public function adisyons()
    {
        return $this->hasMany(Adisyon::class, 'coupon_id');
    }

    public function remainingDay()
    {
        return $this->end_date == null ? 'SÜRESİZ' : ceil(\Carbon\Carbon::now()->diffInDays($this->end_date));
    }
}
