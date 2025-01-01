<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class KasaNotlar extends Model
{
    public $table = 'kasa_notu';

    protected $fillable = ['sube', 'kasa', 'kullanici', 'tarih', 'konu', 'aciklama'];

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->kullanici = auth()->user()->id;
            $model->tarih = date('Y-m-d', strtotime($model->tarih));
        });
    }

    protected $connection = 'best';
}
