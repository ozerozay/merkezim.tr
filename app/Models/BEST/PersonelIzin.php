<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class PersonelIzin extends Model
{
    public $table = 'personel_izin';

    protected $fillable = ['kullanici', 'tarih', 'cesit', 'aciklama', 'saat', 'onaylayan', 'onay_aciklama', 'durum'];

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }
    public function Onaylayani()
    {
        return $this->belongsTo('App\User', 'onaylayan');
    }

    protected $connection = 'best';
}
