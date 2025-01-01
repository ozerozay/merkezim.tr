<?php

namespace App\Models\BEST;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusteriNotlar extends Model
{
    use SoftDeletes;

    protected $table = 'musteri_notlar';

    protected $fillable = ['sube', 'musteri', 'kullanici', 'konu', 'aciklama', 'created_at', 'updated_at'];

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici');
    }

    public function Musterisi()
    {
        return $this->belongsTo('App\Model\Musteri', 'musteri');
    }

    protected $connection = 'best';

    public function getKullaniciAttribute($v)
    {
        return User::find($v)->name;
    }

    public function getCreatedAtAttribute($v)
    {
        return date('d-m-Y H:i:s', strtotime($v));
    }
}
