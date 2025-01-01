<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class PersonelOdemeBildirim extends Model
{
    public $table = 'personel_odeme_bildirim';

    protected $fillable = ['musteri', 'kullanici', 'tarih', 'tutar', 'aciklama', 'onaylayan', 'onaylayan_aciklama', 'durum'];

    public function Musterisi()
    {
        return $this->belongsTo('App\Model\Musteri', 'musteri');
    }
    protected $connection = 'best';
    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }
    public function Onaylayani()
    {
        return $this->belongsTo('App\User', 'onaylayan');
    }
}
