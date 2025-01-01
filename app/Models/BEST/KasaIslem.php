<?php

namespace App\Models\BEST;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class KasaIslem extends Model
{
    use SoftDeletes;

    protected $table = 'kasa_islem';

    protected $casts = [
        'detay' => 'array'
    ];


    protected $connection = 'best';
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (auth()->user()) {
                $model->kullanici = auth()->user()->id;
            }

            $model->tarih = date('Y-m-d', strtotime($model->tarih));
            if (date('Y-m-d') > $model->tarih) {
                $model->durum = 'GEÇMİŞ TARİH';
            } else if (date('Y-m-d') == $model->tarih) {
                $model->durum = 'AYNI GÜN';
            } else {
                $model->durum = 'İLERİ TARİH';
            }
            Cache::forget('kasa' . $model->kasa);
        });
        self::deleted(function ($model) {
            Cache::forget('kasa' . $model->kasa);
        });
    }

    protected $fillable = ['sube', 'kasa', 'tarih', 'cesit', 'tutar', 'detay', 'kullanici', 'musteri', 'durum'];

    public function cesitk()
    {
        return ($this->cesit == 1 ? "Giriş" : 'Çıkış');
    }
    public function Kasasi()
    {
        return $this->belongsTo('\App\Model\Kasa', 'kasa');
    }

    public function Subesi()
    {
        return $this->belongsTo('\App\Model\Sube', 'sube');
    }

    public function Musterisi()
    {
        if ($this->musteri != null && $this->musteri != 0)
            return $this->belongsTo('App\Model\Musteri', 'musteri');
    }

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici')
            ->withDefault([
                'id' => 0,
                'name' => 'Yok'
            ]);
    }
}
