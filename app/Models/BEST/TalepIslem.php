<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class TalepIslem extends Model
{
    public $table = 'talep_islem';

    // çeşit MANUEL - SİSTEM
    protected $fillable = ['talep', 'sube', 'kullanici', 'islem', 'aciklama', 'ilgili_detay', 'tarih', 'geldi', 'created_at', 'updated_at'];


    public function Subesi()
    {
        return $this->belongsTo('App\Model\Sube', 'sube');
    }

    protected $connection = 'best';

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }

    public function Talebi()
    {
        return $this->belongsTo('App\Model\Talep', 'talep');
    }
}
