<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OdemeTakip extends Model
{
    use SoftDeletes;
    public $table = 'odeme_takip';

    protected $fillable = ['sube', 'kullanici', 'sistem_cesit', 'cesit', 'kacay', 'tutar', 'kalan', 'ilgili', 'aciklama', 'tarih', 'islemler'];

    protected $casts = [
        'islemler' => 'array'
    ];

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici', 'id');
    }

    protected $connection = 'best';

    public function Subesi()
    {
        return $this->belongsTo('App\Model\Sube', 'sube', 'id');
    }
}
