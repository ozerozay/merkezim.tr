<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusteriZaman extends Model
{
    // ÇEŞİTLER
    /*
     * HİZMET
     * DURUM
     * SATIŞ
     * SENET
     */
    // İŞLEMLER
    /*
     * SENET - MANUEL EKLEME
     * SENET - AKTARIM
     * SENET - TARİH DEĞİŞİKLİĞİ
     * SENET - SİL
     * HİZMET - RANDEVUSUZ KULLANIM
     * HİZMET - AKTARIM
     * HİZMET - SİL
     * HİZMET - KALAN SEANS
     * HİZMET - TOPLAM SEANS
     * HİZMET - HİZMET DEĞİŞİKLİĞİ
     * HİZMET - SATIŞ DEĞİŞİKLİĞİ
     * HİZMET - MANUEL EKLEME
     * DURUM - DURUM DEĞİŞİKLİĞİ
     * SATIŞ - ONAY - PRİM
     * SATIŞ - ONAY - PRİMSİZ
     * SATIŞ - ONAY - İPTAL
     * SATIŞ - YAPILANDIRMA
     * SATIŞ - AKTARIM
     * SATIŞ - DURUM DEĞİŞİKLİĞİ
     * SATIŞ - DONDURULMA TARİHİ DEĞİŞİKLİĞİ
     * SATIŞ - TARİH DEĞİŞİKLİĞİ
     * SATIŞ - ANALİZ DEĞİŞİKLİĞİ
     * SATIŞ - PERSONEL DEĞİŞİKLİĞİ
     * SATIŞ - TİP DEĞİŞİKLİĞİ
     * SATIŞ - SİL
     */
    use SoftDeletes;

    public $table = 'musteri_zaman';

    protected $fillable = ['sube', 'musteri', 'kullanici', 'tarih', 'islem'];

    protected $casts = [
        'islem' => 'array'
    ];

    protected $connection = 'best';

    public static function booted()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->kullanici = auth()->user()->id;
            $model->tarih = date('Y-m-d');
        });
    }

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }

    public function Subesi()
    {
        return $this->belongsTo('App\Model\Sube', 'sube');
    }

    public function Musterisi()
    {
        return $this->belongsTo('App\Model\Musteri', 'musteri');
    }
}
