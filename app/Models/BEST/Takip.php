<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Takip extends Model
{
    use SoftDeletes;

    public $table = 'takip';

    protected $fillable = ['konu', 'tarih', 'musteri', 'durum', 'kullanici', 'kullanicilar', 'islemler'];

    protected $casts = [
        'kullanicilar' => 'array',
        'islemler' => 'array',
    ];

    protected $connection = 'best';

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->kullanici = auth()->user()->id;
            $model->durum = 'AÇIK';
            $model->tarih = date('Y-m-d', strtotime($model->tarih));
            if ($model->musteri == null)
                $model->musteri = 0;
        });
    }

    public function Mesajlari()
    {
        return $this->hasMany('App\Model\TakipMesaj', 'takip');
    }

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }
}
