<?php

namespace App\Models\BEST;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonelMuhasebe extends Model
{
    use SoftDeletes;

    public $table = 'personel_muhasebe';

    protected $casts = [
        'detay' => 'array'
    ];

    protected $connection = 'best';

    protected $fillable = ['sube', 'personel', 'tarih', 'tutar', 'cesit', 'detay', 'kullanici'];

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici', 'id');
    }

    public function Personeli()
    {
        return $this->belongsTo('App\User', 'personel', 'id');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if ($model->kullanici != 0) {
                $model->kullanici = auth()->user()->id;
            }
            $model->tarih = date('Y-m-d', strtotime($model->tarih));
        });
    }
}
