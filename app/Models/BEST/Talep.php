<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class Talep extends Model
{
    public $table = 'talep';

    // çeşit MANUEL - SİSTEM
    protected $fillable = ['sube', 'kullanici', 'ekleyen', 'aciklama', 'islem_duzelt', 'ad', 'tel', 'tarih', 'ilce', 'teyit', 'durum', 'durum_anlik', 'cesit', 'islemler', 'created_at', 'updated_at'];

    protected $casts = [
        'islemler' => 'array'
    ];

    protected $connection = 'best';

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            /*if (!isset($model->kullanici))
            {
                $model->kullanici = auth()->user()->id;
            }*/

            $model->ad = mb_strtoupper($model->ad);
            if (!$model->durum) {
                //$model->durum = 'BEKLENİYOR';
            }
            if (!isset($model->ekleyen)) {
                $model->ekleyen = auth()->user()->id;
            }

            $model->tarih = date('Y-m-d');
        });
    }

    public function Subesi()
    {
        return $this->belongsTo('App\Model\Sube', 'sube');
    }

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }

    public function Islemleri()
    {
        return $this->hasMany('App\Model\TalepIslem', 'talep');
    }

    public function Arandiklari()
    {
        return $this->hasMany('App\Model\Arama', 'arayan', 'tel');
    }
    public function Aradiklari()
    {
        return $this->hasMany('App\Model\Arama', 'aranan', 'tel');
    }

    public function Aramalari()
    {
        return $this->Arandiklari->merge($this->Aradiklari);
    }
}
