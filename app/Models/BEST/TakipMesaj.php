<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TakipMesaj extends Model
{
    use SoftDeletes;

    public $table = 'takip_mesaj';

    protected $fillable = ['takip', 'kullanici', 'mesaj'];

    protected $connection = 'best';

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->kullanici = auth()->user()->id;
        });
    }

    public function Kullanicisi()
    {
        return $this->belongsTo('App\user', 'kullanici');
    }
}
