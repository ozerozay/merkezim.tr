<?php

namespace App\Models\BEST;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusteriSenet extends Model
{
    use SoftDeletes;

    protected $table = 'musteri_senet';

    protected $fillable = ['sube', 'musteri', 'satis', 'cesit', 'kalan', 'tutar', 'durum', 'tarih', 'kullanici'];

    protected $appends = ['mevcut'];

    protected $connection = 'best';

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            //$model->kullanici = auth()->user()->id;
            //$model->tarih = date('Y-m-d', strtotime($model->tarih));
        });
    }

    public function scopeAktifler($q)
    {
        return $q->where('kalan', '>', 0)->where('durum', 'Aktif')->orderBy('tarih', 'asc');
    }

    public function scopeTers($q)
    {
        return $q->whereRaw('kalan != tutar')->where('durum', 'Aktif')->orderBy('tarih', 'desc');
    }

    public function getMevcutAttribute($t)
    {
        $t = date('Y-m-d');
        $odeme_durumu = "";
        if ($this->kalan > 0) {
            if (date('Y-m-d', strtotime($this->tarih)) < $t) {
                $odeme_durumu = 'GECİKMİŞ ÖDEME';
            } else {
                $odeme_durumu = 'BEKLENİYOR';
            }
        } else {
            $odeme_durumu = 'ÖDENDİ';
        }
        return $odeme_durumu;
    }

    public function Satisi()
    {
        return $this->belongsTo(MusteriSatis::class, 'satis');
    }

    public function Musterisi()
    {
        return $this->belongsTo(Musteri::class, 'musteri');
    }

    public function Subesi()
    {
        return $this->belongsTo(Sube::class, 'sube');
    }

    public function Kullanicisi()
    {
        return $this->belongsTo(User::class, 'kullanici');
    }
}
