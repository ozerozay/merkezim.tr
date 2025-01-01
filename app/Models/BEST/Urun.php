<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Urun extends Model
{
    use SoftDeletes;

    public $table = 'urun';

    protected $fillable = ['sube', 'ad', 'fiyat', 'tedarikci', 'stok', 'aktif'];

    public function Subesi()
    {
        return $this->belongsTo('App\Model\Sube', 'sube');
    }

    protected $connection = 'best';

    public function Tedarikcisi()
    {
        return $this->belongsTo('App\Model\Tedarikci', 'tedarikci');
    }

    public function Detayi()
    {
        return $this->hasMany('App\Model\UrunDetay', 'urun');
    }
}
