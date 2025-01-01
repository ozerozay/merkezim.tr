<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arama extends Model
{
    use SoftDeletes;
    public $table = 'arama';

    protected $casts = [
        'api' => 'array',
        'sistem' => 'array'
    ];
    protected $connection = 'best';

    protected $hidden = ['api', 'sistem'];

    protected $fillable = ['arayan', 'aranan', 'sonuc', 'kimlik', 'cesit', 'api', 'sistem', 'sure'];

    public function getCesitAttribute($v)
    {
        return ($v == 1 ? "SİSTEM" : "MANUEL");
    }

    public function ArayanBul()
    {
        if ($this->arayan == '08502411010' || $this->arayan == '908502411010') {
            return [
                'cesit' => 'MARGE',
                'ad' => 'MARGE'
            ];
        }
        /*$kullanici = \App\User::whereJsonContains('dahili', $this->arayan)->first();
        if ($kullanici)
        {
            return [
                'cesit' => 'PERSONEL',
                'ad' => $kullanici->name
            ];
        }*/
        $musteri = \App\Model\Musteri::where('tel', $this->arayan)->orWhere('tel', substr($this->arayan, -10))->first();
        if ($musteri) {
            return [
                'cesit' => 'MÜŞTERİ',
                'ad' => $musteri->ad
            ];
        }
        $talep = \App\Model\Talep::where('tel', $this->arayan)->orWhere('tel', substr($this->arayan, -10))->first();
        if ($talep) {
            return [
                'cesit' => 'TALEP',
                'ad' => $talep->ad
            ];
        }

        return [
            'cesit' => 'DİĞER',
            'ad' => $this->arayan
        ];
    }

    public function ArananBul()
    {
        if ($this->aranan == '08502411010' || $this->aranan == '908502411010') {
            return [
                'cesit' => 'MARGE',
                'ad' => 'MARGE'
            ];
        }
        /*$kullanici = \App\User::whereJsonContains('dahili', $this->aranan)->first();
        if ($kullanici)
        {
            return [
                'cesit' => 'PERSONEL',
                'ad' => $kullanici->name
            ];
        }*/
        $musteri = \App\Model\Musteri::where('tel', $this->aranan)->orWhere('tel', substr($this->aranan, -10))->first();
        if ($musteri) {
            return [
                'cesit' => 'MÜŞTERİ',
                'ad' => $musteri->ad
            ];
        }

        $talep = \App\Model\Talep::where('tel', $this->aranan)->orWhere('tel', substr($this->aranan, -10))->first();
        if ($talep) {
            return [
                'cesit' => 'TALEP',
                'ad' => $talep->ad
            ];
        }

        return [
            'cesit' => 'DİĞER',
            'ad' => $this->aranan
        ];
    }
}
