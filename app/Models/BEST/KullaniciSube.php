<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class KullaniciSube extends Model
{

    public $timestamps = false;

    protected $table = 'kullanici_sube';

    protected $fillable = [
        'kullanici', 'sube'
    ];

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici', 'id');
    }

    public function Subesi()
    {
        return $this->belongsTo('App\Model\Sube', 'sube', 'id');
    }

    protected $connection = 'best';
}
