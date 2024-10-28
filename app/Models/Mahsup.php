<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahsup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function giris_kasa()
    {
        return $this->belongsTo(Kasa::class, 'giris_kasa_id');
    }

    public function cikis_kasa()
    {
        return $this->belongsTo(Kasa::class, 'cikis_kasa_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction_giris()
    {
        return $this->belongsTo(Transaction::class, 'transaction_giris_id');
    }

    public function transaction_cikis()
    {
        return $this->belongsTo(Transaction::class, 'transaction_cikis_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transacable');
    }
}
