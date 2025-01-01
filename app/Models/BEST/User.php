<?php

namespace App\Models\BEST;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class User extends Authenticatable
{
    use HasJsonRelationships, HasRoles, Notifiable;

    protected $casts = [
        'sube' => 'array',
        'dahili' => 'array',
        'bildirim' => 'array',
    ];

    protected $connection = 'best';

    protected $fillable = [
        'telefon', 'yetki', 'telefon_kod', 'name', 'password', 'remember_token', 'aktif', 'sube', 'web_push', 'dahili', 'bildirim',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function bildir($cesit, $kullanicilar, $mesaj, $icon, $renk)
    {
        $kullanicilar = User::whereIn('id', $kullanicilar)->get();
        foreach ($kullanicilar as $k) {
            $data = (object) [
                'title' => $cesit,
                'msg' => $mesaj,
                'icon' => $icon,
                'category' => $renk,
                'time' => date('Y-m-d H:i:s'),
                'index' => 0,
            ];
            //$k->notify(new \App\Notifications\SatisEklendi($data));
        }
    }

    public static function topluBildir($cesit, $sube, $mesaj, $icon, $renk)
    {
        $kullanicilar = \App\User::whereJsonContains('sube', $sube);

        if ($cesit != 'YÖNETİCİ BİLDİRİM') {
            $kullanicilar = $kullanicilar->whereJsonContains('bildirim', $cesit);
        }

        foreach ($kullanicilar->get() as $k) {
            if ($k->hasRole('YÖNETİCİ') || $k->hasRole('MÜDÜR')) {
                $data = (object) [
                    'title' => $cesit,
                    'msg' => $mesaj,
                    'icon' => $icon,
                    'category' => $renk,
                    'time' => date('Y-m-d H:i:s'),
                    'index' => 0,
                ];
                //$k->notify(new \App\Notifications\SatisEklendi($data));
            }
        }
    }

    public function Subesi()
    {
        return $this->belongsToJson('App\Model\Sube', 'sube');
    }

    public function aktif()
    {
        return $this->where('aktif', 1);
    }

    public function AktifHediyeCekleri()
    {
        return $this->hasMany('App\Model\Hediye', 'atanan', 'id')->where('kullanilan_tarih', null);
    }

    public function Muhasebesi()
    {
        return $this->hasMany('App\Model\PersonelMuhasebe', 'personel');
    }

    public function Notlari()
    {
        return $this->hasMany('App\Model\Notlar', 'kullanici');
    }

    public function Dosyalari()
    {
        return $this->hasMany('App\Model\Dosya', 'kullanici');
    }

    public function TalepIslemleri()
    {
        return $this->hasMany('App\Model\TalepIslem', 'kullanici');
    }

    public function Aramalari($b, $bi)
    {
        return \App\Model\Arama::whereIn('arayan', $this->dahili)
            ->orWhereIn('aranan', $this->dahili)
            ->whereRaw('DATE(created_at) between ? and ?', [date('Y-m-d', strtotime($b)), date('Y-m-d', strtotime($bi))])
            ->get();
    }

    public function Izinleri()
    {
        return $this->hasMany('App\Model\PersonelIzin', 'kullanici');
    }

    public function Satis()
    {
        return $this->hasMany('App\Models\BEST\MusteriSatis', 'musteri');
    }

    public function Satislari($tarih, $personel)
    {
        return \App\Model\MusteriSatis::whereJsonContains('personel', $personel)->where('tarih', $tarih)->where('tutar', '>', '399')->where('durum', 'Aktif')->count();
    }

    public function KonusmaSuresiTarihli($baslangic, $bitis)
    {
        $sure = 0;
        foreach ($this->dahili as $d) {
            $sure += \App\Model\Arama::whereRaw('DATE(created_at) between ? and ?', [$baslangic, $bitis])
                ->where(function ($q) use ($d) {
                    $q->orWhere('arayan', $d)->orWhere('aranan', $d);
                })->sum('sure');
        }

        return $sure;
    }

    public function KonusmaSuresi()
    {
        $sure = 0;
        foreach ($this->dahili as $d) {
            $sure += \App\Model\Arama::whereRaw('DATE(created_at) = ?', date('Y-m-d'))
                ->where(function ($q) use ($d) {
                    $q->orWhere('arayan', $d)->orWhere('aranan', $d);
                })->sum('sure');
        }

        return $sure;
    }

    public function AramaSayisiTarihli($baslangic, $bitis)
    {
        $sure = 0;
        foreach ($this->dahili as $d) {
            $sure += \App\Model\Arama::whereRaw('DATE(created_at) between ? and ?', [$baslangic, $bitis])
                ->where(function ($q) use ($d) {
                    $q->orWhere('arayan', $d)->orWhere('aranan', $d);
                })->count();
        }

        return $sure;
    }

    public function AramaSayisi()
    {
        $sure = 0;
        foreach ($this->dahili as $d) {
            $sure += \App\Model\Arama::whereRaw('DATE(created_at) = ?', date('Y-m-d'))
                ->where(function ($q) use ($d) {
                    $q->orWhere('arayan', $d)->orWhere('aranan', $d);
                })->count();
        }

        return $sure;
    }

    public function findForPassport($telefon)
    {
        return $this->where('telefon', $telefon)->first();
    }

    public function validateForPassportPasswordGrant($telefon_kod)
    {
        return $this->where('telefon_kod', $telefon_kod)->first();
    }

    public function routeNotificationForSlack($notification)
    {
        return 'https://hooks.slack.com/services/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';
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }

    public function AramaPersoneli($bilgi, $baslangic, $bitis)
    {
        $kullanici = $this;

        switch ($bilgi) {
            case 'randevu_sayisi':
                return \App\Model\Randevu::whereBetween('tarih', [$baslangic, $bitis])
                    ->where('kullanici', $this->id)
                    ->where('durum', 'Aktif')
                    ->count();
                break;

            case 'musteri_not':
                return \App\Model\MusteriNotlar::where('kullanici', $this->id)
                    ->whereRaw('DATE(created_at) between ? and ?', [$baslangic, $bitis])
                    ->count();
                break;

            case 'sms_sayisi':
                return \App\Model\SMS::where('kullanici', $this->id)
                    ->whereRaw('DATE(created_at) between ? and ?', [$baslangic, $bitis])
                    ->count();
                break;
        }
    }

    public function SatisPersonel($bilgi, $baslangic, $bitis)
    {
        $kullanici = $this;

        switch ($bilgi) {
            case 'satis_tutar':
                return \App\Model\MusteriSatis::whereBetween('tarih', [$baslangic, $bitis])
                    ->whereJsonContains('personel', $kullanici->id)
                    ->where('durum', 'Aktif')
                    ->sum('tutar');
                break;

            case 'satis_sayisi':
                return \App\Model\MusteriSatis::whereBetween('tarih', [$baslangic, $bitis])
                    ->whereJsonContains('personel', $kullanici->id)
                    ->where('durum', 'Aktif')
                    ->count();
                break;

            case 'satis_iptal':
                return \App\Model\MusteriSatis::whereBetween('tarih', [$baslangic, $bitis])
                    ->whereJsonContains('personel', $kullanici->id)
                    ->where('durum', 'İptal')
                    ->count();
                break;

            case 'satis_iptal_tutar':
                return \App\Model\MusteriSatis::whereBetween('tarih', [$baslangic, $bitis])
                    ->whereJsonContains('personel', $kullanici->id)
                    ->where('durum', 'İptal')
                    ->sum('tutar');
                break;

            case 'talep_sayisi':

                return \App\Model\Talep::whereBetween('tarih', [$baslangic, $bitis])
                    ->where('kullanici', $kullanici->id)
                    ->count();
                break;

            case 'talep_islem_sayisi':

                return \App\Model\TalepIslem::where('kullanici', $kullanici->id)
                    ->whereRaw('DATE(created_at) between ? and ?', [$baslangic, $bitis])
                    ->count();

                break;

            case 'talep_randevu_sayisi':
                return \App\Model\TalepIslem::where('islem', 'RANDEVU')
                    ->where('kullanici', $kullanici->id)
                    ->whereRaw('DATE(created_at) between ? and ?', [$baslangic, $bitis])
                    ->count();
                break;

            case 'talep_satis_sayisi':

                return \App\Model\Talep::whereBetween('tarih', [$baslangic, $bitis])
                    ->where('kullanici', $kullanici->id)
                    ->where('durum', 'SATIŞ')
                    ->count();
                break;
        }
    }

    public function HizmetPersonel($bilgi, $baslangic, $bitis)
    {
        $kullanici = $this;
        switch ($bilgi) {
            case 'randevu_sayisi':

                return \App\Model\Randevu::whereBetween('tarih', [$baslangic, $bitis])
                    ->whereJsonContains('detay', ['onay_personel' => $kullanici->id])
                    ->where('durum', 'Onaylandı')
                    ->count();

                break;

            case 'randevu_dakika':
                return \App\Model\Randevu::whereBetween('tarih', [$baslangic, $bitis])
                    ->whereJsonContains('detay', ['onay_personel' => $kullanici->id])
                    ->where('durum', 'Onaylandı')
                    ->sum('sure');
                break;

            case 'satis_sayisi':
                return \App\Model\MusteriSatis::whereBetween('tarih', [$baslangic, $bitis])
                    ->whereJsonContains('personel', $kullanici->id)
                    ->where('durum', 'Aktif')
                    ->count();
                break;

            case 'satis_tutari':
                return \App\Model\MusteriSatis::whereBetween('tarih', [$baslangic, $bitis])
                    ->whereJsonContains('personel', $kullanici->id)
                    ->where('durum', 'Aktif')
                    ->sum('tutar');
                break;
        }
    }
}
