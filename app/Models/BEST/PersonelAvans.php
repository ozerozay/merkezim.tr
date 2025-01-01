<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class PersonelAvans extends Model
{
    public $table = 'personel_avans';

    protected $fillable = ['kullanici', 'tutar', 'aciklama', 'onaylayan', 'onay_aciklama', 'durum', 'tarih'];

    public function Onaylayan()
    {
        return $this->belongsTo('App\User', 'onaylayan');
    }

    protected $connection = 'best';

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }
}
