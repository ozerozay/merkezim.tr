<?php

namespace App\Models\BEST;

use App\Models\BEST\Randevu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Musteri extends Model
{
    use SoftDeletes, HasJsonRelationships;
    public $table = 'musteri';

    protected $fillable = [
        'sube', 'ad', 'tckimlik', 'eposta', 'dtarih', 'il', 'ilce', 'adres', 'tel', 'tel_diger', 'referans', 'talep', 'durum', 'aktar'
    ];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['deleted_at'];

    protected $casts = [
        'durum' => 'array'
    ];

    protected $connection = 'best';

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->ad = mb_strtoupper($model->ad);
            $model->adres = mb_strtoupper($model->adres);
            $model->dtarih = date('Y-m-d', strtotime($model->dtarih));
        });
    }

    public function Subesi()
    {
        return $this->belongsTo(Sube::class, 'sube', 'id');
    }

    public function Senetleri()
    {
        return $this->hasMany(MusteriSenet::class, 'musteri');
    }

    public function Satislari()
    {
        return $this->hasMany(MusteriSatis::class, 'musteri');
    }

    public function HizmetleriHepsi()
    {
        return $this->hasMany(MusteriHizmet::class, 'musteri');
    }

    public function Hizmetleri($durum = 'Aktif')
    {
        return $this->hasMany(MusteriHizmet::class, 'musteri')
            ->where('durum', $durum);
    }

    public function Randevulari()
    {
        return $this->hasMany(Randevu::class, 'musteri');
    }

    public function Notlari()
    {
        return $this->hasMany(MusteriNotlar::class, 'musteri');
    }

    public function Durumlari()
    {
        return $this->belongsToJson(Durum::class, 'durum');
    }

    public function KasaIslemleri()
    {
        return $this->hasMany(KasaIslem::class, 'musteri');
    }

    public function Takipleri()
    {
        return $this->hasMany(Takip::class, 'musteri');
    }

    public function Referanslari()
    {
        return $this->hasMany(Musteri::class, 'referans');
    }

    public function ZamanTuneli()
    {
        return $this->hasMany(MusteriZaman::class, 'musteri');
    }

    public function DurumKontrol($durum)
    {
        if ($this->Durumlari()->whereJsonContains('ozellik', $durum)->count() > 0)
            return true;

        return false;

        /*
        foreach($this->Durumlari()->get() as $d)
        {
            foreach($d->ozellik as $o)
            {
                if ($durum == $o)
                    return true;
            }
        }

        return false;
        */
    }

    public function Urunleri()
    {
        return $this->hasMany(UrunDetay::class, 'musteri');
    }
    public function KalanSeansToplam()
    {
        return $this->Hizmetleri()->where('durum', 'Aktif')
            ->where('kalan_seans', '>', '0')
            ->sum('kalan_seans');
    }

    public function KalanSeanslar()
    {
        return $this->Hizmetleri()->where('durum', 'Aktif')
            ->where('kalan_seans', '>', '0')
            ->get();
    }

    public function KalanOdemeleriTutar()
    {
        return $this->Senetleri()->where('durum', 'Aktif')
            ->where('kalan', '>', '0')
            ->sum('kalan');
    }

    public function KalanTaksitSayisi()
    {
        return $this->Senetleri()->where('durum', 'Aktif')
            ->where('kalan', '>', '0')
            ->count();
    }

    public function KalanTaksitler()
    {
        return $this->Senetleri()->where('durum', 'Aktif')
            ->where('kalan', '>', '0')
            ->get();
    }

    public function GecikmisOdemeleri()
    {
        return $this->Senetleri()->where('durum', 'Aktif')
            ->where('tarih', '<=', date('Y-m-d'))
            ->where('kalan', '>', '0')
            ->count();
    }

    public function GecikmisOdemeleriTutar()
    {
        return $this->Senetleri()->where('durum', 'Aktif')
            ->where('tarih', '<=', date('Y-m-d'))
            ->where('kalan', '>', '0')
            ->sum('kalan');
    }

    public function Arandiklari()
    {
        return $this->hasMany(Arama::class, 'arayan', 'tel');
    }
    public function Aradiklari()
    {
        return $this->hasMany(Arama::class, 'aranan', 'tel');
    }
    public function Aramalari()
    {
        return $this->Arandiklari->merge($this->Aradiklari);
    }

    public function SMSleri()
    {
        return $this->hasMany(SMS::class, 'tel', 'tel');
    }

    public function SozlezmeNumaralari()
    {
        $a = '';
        foreach ($this->Satislari()->get() as $s) {
            $a .= $s->numara . ', ';
        }
        $a = substr($a, 0, strlen($a) - 2);
        return $a;
    }

    public function Durumlar()
    {
        $a = '';
        foreach ($this->Durumlari()->get() as $d) {
            $a .= $d->ad . ', ';
        }
        $a = substr($a, 0, strlen($a) - 2);
        return $a;
    }

    public function bilgi($bilgi)
    {
        switch ($bilgi) {
            case 'hizmet_oran':
                if ($this->Hizmetleri()->aktif()->sum('toplam_seans') > 0) {
                    return round((100 * $this->Hizmetleri()->aktif()->sum('kalan_seans')) / $this->Hizmetleri()->aktif()->sum('toplam_seans'));
                } else {
                    return 0;
                }
                break;

            case 'aktif_randevu':
                return $this->Randevulari()->where('tarih', '>=', date('Y-m-d'))->where('durum', 'Bekleniyor')->count();
                break;

            case 'aktif_takip':
                return $this->Takipleri()->where('durum', 'AÇIK')->count();
                break;
        }
    }
}
