<?php

namespace App\Models\BEST;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusteriSatis extends Model
{
    // Durumlar
    // Aktif, Onay Bekliyor, Donduruldu, İptal
    use SoftDeletes;

    public $table = 'musteri_satis';

    protected $fillable = [
        'sube', 'musteri', 'numara', 'numara_eski', 'tip', 'tarih', 'durum', 'tutar', 'indirimsiz_tutar', 'personel', 'analiz', 'prim_detay', 'prim', 'aktif_tarih', 'kullanici', 'numara_eski_sirali',
    ];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['deleted_at'];

    protected $connection = 'best';

    protected $casts = [
        //'personel' => Json::class,
        //'prim_detay' => Json::class,
        'tarih' => 'date:Y-m-d',
        'created_at' => 'date:Y-m-d',
        'personel' => 'array',
    ];

    public static function booted()
    {
        parent::boot();

        self::saved(function ($satis) {
            if ($satis->isDirty('durum')) {
                $satis->Hizmetleri()->update(['durum' => $satis->durum]);
                if ($satis->durum != 'Donduruldu') {
                    $satis->Senetleri()->update(['durum' => $satis->durum]);
                }
            }
        });

        self::creating(function ($model) {
            //$model->kullanici = auth()->user()->id;
            //$model->tarih = date('Y-m-d', strtotime($model->tarih));
        });
    }

    public function scopeAktif($q)
    {
        return $q->where('durum', 'Aktif');
    }

    public function PersonelIsim()
    {
        $isimler = '';
        foreach ($this->personel as $p) {
            $isimler .= User::find($p)->name.', ';
        }

        return substr($isimler, 0, (strlen($isimler) - 2));
    }

    public static function Durumlar()
    {
        return [
            'Aktif', 'Onay Bekliyor', 'Donduruldu', 'İptal',
        ];
    }

    public function getNumaraAttribute($v)
    {
        if ($v == 0) {
            return $this->numara_eski;
        }

        return $v;
    }

    public function GecikmisOdemesi()
    {
        return $this->Senetleri()->where('durum', 'Aktif')
            ->where('tarih', '<=', date('Y-m-d'))
            ->where('kalan', '>', '0')
            ->count();
    }

    public function GecikmisOdemesiTutar()
    {
        return $this->Senetleri()->where('durum', 'Aktif')
            ->where('tarih', '<=', date('Y-m-d'))
            ->where('kalan', '>', '0')
            ->sum('kalan');
    }

    public function Tahsilati()
    {
        $senet_tutar = $this->Senetleri()->sum('tutar') - $this->Senetleri()->sum('kalan');
        $pesinat = KasaIslem::whereJsonContains('detay->satis_id', $this->id)->sum('tutar');

        return $senet_tutar + $pesinat;
    }

    public function HizmetYazi()
    {
        $hizmetler = $this->Hizmetleri()->with('Hizmeti')->get();
        $hizmet_yazi = '';
        foreach ($hizmetler as $hizmet) {
            $hizmet_yazi .= $hizmet->toplam_seans.' - '.$hizmet->Hizmeti->ad.',';
        }

        return $hizmet_yazi;
    }

    public function KalanTutar()
    {
        return $this->Senetleri()->where('durum', 'Aktif')
            ->where('kalan', '>', 0)
            ->sum('kalan');
    }

    public function Subesi()
    {
        return $this->belongsTo('App\Model\BEST\Sube', 'sube', 'id');
    }

    public function Kullanicisi()
    {
        return $this->belongsTo('App\Models\BEST\User', 'kullanici', 'id');
    }

    public function Analizci()
    {
        return $this->belongsTo('App\Models\BEST\User', 'analiz', 'id');
    }

    public function Hizmetleri()
    {
        return $this->hasMany(MusteriHizmet::class, 'satis');
    }

    public function Musterisi()
    {
        return $this->belongsTo('App\Model\BEST\Musteri', 'musteri');
    }

    public function Senetleri()
    {
        return $this->hasMany(MusteriSenet::class, 'satis');
    }

    public function SatisTipi()
    {
        return $this->belongsTo('App\Model\BEST\SatisTipi', 'tip');
    }

    public function Tipi()
    {
        return $this->belongsTo('App\Model\BEST\SatisTipi', 'tip');
    }

    public function Pesinati()
    {
        return KasaIslem::whereJsonContains('detay', ['satis_id' => $this->id, 'islem' => 'PEŞİNAT'])->get();
    }

    public function PrimHesaplaAnaliz()
    {
        // ANALİZ PRİM KONTROL
        $detay = $this->prim_detay;

        if ($detay['analiz_durum'] != 'Onaylandı') {
            $analizci = User::find($this->analiz);
            if ($analizci) {
                if ($analizci->hasRole('ESTETİSYEN')) {
                    $analiz_durum = 'Onaylandı';
                    $analiz_tutar = SatisTipi::where('id', $this->tip)->first()->analiz;
                    $pm = PersonelMuhasebe::create([
                        'sube' => $this->sube,
                        'personel' => $analizci->id,
                        'tarih' => date('Y-m-d'),
                        'cesit' => 2,
                        'tutar' => $analiz_tutar,
                        'detay' => [
                            'islem' => 'MAHSUP',
                            'aciklama' => $this->numara.' nolu satıştan alınan analiz ücreti.',
                            'konu' => 'PRİM - ANALİZ',
                            'kasa_id' => 0,
                        ],
                    ]);
                } else {
                    $analiz_durum = 'ESTETİSYEN DEĞİL';
                    $analiz_tutar = 0;
                }
                $detay['analiz_durum'] = $analiz_durum;
                $detay['analiz_tutar'] = $analiz_tutar;
                $this->prim_detay = $detay;
                $this->save();
            }
        }
    }

    public function PrimHesaplaEstetisyen()
    {
        $satis = $this;
        $estetisyen_prim = $satis->Tipi->estetisyen;
        $analiz_prim = $satis->Tipi->analiz;
        $prim_detay = $satis->prim_detay;

        if ($satis->tutar > 399) {
            foreach ($satis->personel as $personel) {
                $kull = User::find($personel);
                if ($kull->hasRole('ESTETİSYEN')) {
                    $prim_detay['estetisyen_durum'] = 'Onaylandı';
                    $prim_detay['estetisyen_tutar'] = $estetisyen_prim;
                    $pm3 = PersonelMuhasebe::create([
                        'sube' => $satis->sube,
                        'personel' => $kull->id,
                        'tarih' => date('Y-m-d'),
                        'cesit' => 2,
                        'kullanici' => 0,
                        'tutar' => $estetisyen_prim,
                        'detay' => [
                            'islem' => 'MAHSUP',
                            'aciklama' => $satis->numara.' nolu satıştan alınan estetisyen prim ücreti',
                            'konu' => 'PRİM - ESTETİSYEN',
                            'kasa_id' => 0,
                        ],
                    ]);
                    $prim_detay['estetisyen_id'] = $pm3->id;
                }
                if ($satis->analiz == $kull->id) {
                    $prim_detay['analiz_durum'] = 'Onaylandı';
                    $prim_detay['analiz_tutar'] = $analiz_prim;
                    $pm2 = PersonelMuhasebe::create([
                        'sube' => $satis->sube,
                        'personel' => $kull->id,
                        'tarih' => date('Y-m-d'),
                        'cesit' => 2,
                        'kullanici' => 0,
                        'tutar' => $analiz_prim,
                        'detay' => [
                            'islem' => 'MAHSUP',
                            'aciklama' => $satis->numara.' nolu satıştan alınan analiz ücreti',
                            'konu' => 'PRİM - ANALİZ',
                            'kasa_id' => 0,
                        ],
                    ]);
                    $prim_detay['analiz_id'] = $pm2->id;
                }
            }
            $satis->prim_detay = $prim_detay;
            if ($satis->save()) {
                return true;
            }
        }

        return false;
    }

    public function PrimHesaplaSatis()
    {
        $satis = $this;
        $tip_prim = $satis->Tipi->prim;
        $analiz_prim = $satis->Tipi->analiz;
        $estetisyen_prim = $satis->Tipi->estetisyen;
        // 3-5 Satış Primi
        // '3-5_durum' => 'Peşinat Bekleniyor',
        // '3-5_tutar' => 0,
        // Gün içinde sokulan tutarın %5'i
        // 50 TL ve üzeri peşinatta

        $prim_detay = $satis->prim_detay;

        if ($satis->tutar > 399) {
            foreach ($satis->personel as $personel) {
                if ($tip_prim > 0) {
                    $pesinat = $satis->Pesinati()->sum('tutar');
                    if ($pesinat > 49) {
                        $kull = User::find($personel);
                        if ($kull->hasRole('SATIŞ')) {
                            $prim_detay['yuzde_durum'] = 'Onaylandı';
                            $prim_detay['yuzde_tutar'] = round(($pesinat * 5) / 100);
                            $prim_detay['tip_durum'] = 'Onaylandı';
                            $prim_detay['tip_tutar'] = $tip_prim;

                            $pm = PersonelMuhasebe::create([
                                'sube' => $satis->sube,
                                'personel' => $kull->id,
                                'tarih' => date('Y-m-d'),
                                'cesit' => 2,
                                'tutar' => $prim_detay['yuzde_tutar'],
                                'kullanici' => 0,
                                'detay' => [
                                    'islem' => 'MAHSUP',
                                    'aciklama' => $satis->numara.' nolu satıştan alınan %5 primi',
                                    'konu' => 'PRİM - YÜZDE',
                                    'kasa_id' => 0,
                                ],
                            ]);

                            $prim_detay['yuzde_id'] = $pm->id;

                            $pm2 = PersonelMuhasebe::create([
                                'sube' => $satis->sube,
                                'personel' => $kull->id,
                                'tarih' => date('Y-m-d'),
                                'cesit' => 2,
                                'kullanici' => 0,
                                'tutar' => $prim_detay['tip_tutar'],
                                'detay' => [
                                    'islem' => 'MAHSUP',
                                    'aciklama' => $satis->numara.' nolu satıştan alınan satış tipi primi',
                                    'konu' => 'PRİM - TİP',
                                    'kasa_id' => 0,
                                ],
                            ]);

                            $prim_detay['tip_id'] = $pm2->id;

                            $personelduzenli[] = $personel;

                            //\App\Models\BEST\User::bildir('SATIŞ ONAYLANDI', $personelduzenli, $satis->numara . ' nolu satış onaylandı.', 'IconUser', 'warning');
                        }
                    }
                }
            }
            $satis->prim_detay = $prim_detay;
            if ($satis->save()) {
                return true;
            }
        }

        return false;
    }
}
