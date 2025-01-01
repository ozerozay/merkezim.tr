<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UrunDetay extends Model
{
    use SoftDeletes;

    public $table = 'urun_detay';

    protected $fillable = ['urun', 'cesit', 'adet', 'tutar', 'musteri', 'aciklama', 'kullanici', 'satan'];

    public static function booted()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->kullanici = auth()->user()->id;
        });
    }

    protected $connection = 'best';

    public function Urunu()
    {
        return $this->belongsTo('App\Model\Urun', 'urun');
    }

    public function Musterisi()
    {
        return $this->belongsTo('App\Model\Musteri', 'musteri');
    }

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }
}
