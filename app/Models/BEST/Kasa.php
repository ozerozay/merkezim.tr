<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kasa extends Model
{
    use SoftDeletes;

    public $table = 'kasa';

    protected $fillable = [
        'sube', 'ad', 'tip', 'aktif'
    ];

    public $timestamps = true;

    public function Subesi()
    {
        return $this->belongsTo('App\Model\Sube', 'sube', 'id');
    }

    public function Islemleri()
    {
        return $this->hasMany('App\Model\KasaIslem', 'kasa');
    }

    public function Notlari()
    {
        return $this->hasMany('App\Model\KasaNotlar', 'kasa');
    }

    protected $connection = 'best';
}
