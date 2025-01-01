<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Randevu extends Model
{
    use SoftDeletes;

    // Durumlar
    // Bekleniyor, Yönlendirildi, Onaylandı, İptal, Gelmedi

    public $table = 'randevu';

    public $timestamps = true;

    public $dates = ['baslangic', 'bitis', 'updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'sube', 'durum', 'cesit', 'tarih', 'baslangic', 'bitis', 'hizmetler',
        'sure', 'oda', 'kullanici', 'musteri', 'detay', 'islemler'
    ];

    public static function Durumlar()
    {
        return [
            'Bekleniyor', 'Yönlendirildi', 'Onaylandı', 'İptal', 'Gelmedi'
        ];
    }

    protected $connection = 'best';

    protected $casts = [
        'detay' => 'array',
        'islemler' => 'array',
        'hizmetler' => 'array'
    ];

    public function scopeAktifler($q)
    {
        return $q->where('durum', '!=', 'İptal');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($m) {
            //$m->kullanici = auth()->user()->id;
        });
    }

    public function HizmetIsim()
    {
        $isimler = '';
        foreach ($this->hizmetler as $p) {
            $isim = MusteriHizmet::find($p);
            if ($isim)
                $isim = $isim->Hizmeti->ad;
            else
                $isim = 'yok';
            $isimler .= $isim . ',';
        }
        return substr($isimler, 0, (strlen($isimler) - 1));
    }

    public function Subesi()
    {
        return $this->belongsTo('App\Models\BEST\Sube', 'sube', 'id');
    }

    public function Musterisi()
    {
        return $this->belongsTo('App\Models\BEST\Musteri', 'musteri');
    }

    public function Kullanicisi()
    {
        return $this->belongsTo('App\Models\BEST\User', 'kullanici')
            ->withDefault([
                'id' => 0,
                'name' => 'Yok'
            ]);
    }

    public function Odasi()
    {
        return $this->belongsTo('App\Models\BEST\HizmetOda', 'oda');
    }
}
