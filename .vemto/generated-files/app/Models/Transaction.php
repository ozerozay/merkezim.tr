<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['date'];

    public function kasa()
    {
        return $this->belongsTo(Kasa::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function masraf()
    {
        return $this->belongsTo(Masraf::class);
    }

    public function mahsupGiris()
    {
        return $this->hasMany(Mahsup::class, 'transaction_giris_id');
    }

    public function mahsupCikis()
    {
        return $this->hasMany(Mahsup::class, 'transaction_cikis_id');
    }

    public function transacable()
    {
        return $this->morphTo();
    }

    public function transacable()
    {
        return $this->morphTo();
    }
}
