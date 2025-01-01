<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class DosyaIzin extends Model
{
    public $table = 'dosya_izin';

    protected $fillable = ['kullanici', 'izin', 'durum'];

    protected $connection = 'best';

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }
}
