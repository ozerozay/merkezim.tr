<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class TedarikciMuhasebe extends Model
{
    public $table = 'tedarikci_muhasebe';

    protected $fillable = ['sube', 'tedarikci', 'cesit', 'tarih', 'tutar', 'detay', 'kullanici'];

    protected $casts = [
        'detay' => 'array'
    ];

    protected $connection = 'best';

    public static function booted()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->kullanici = auth()->user()->id;
            $model->tarih = date('Y-m-d', strtotime($model->tarih));
        });
    }

    public function Tedarikcisi()
    {
        return $this->belongsTo('App\Model\Tedarik', 'tedarikci');
    }
}
