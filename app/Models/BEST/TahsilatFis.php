<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class TahsilatFis extends Model
{
    public $table = 'fis_tahsilat';

    protected $fillable = ['musteri', 'odenen', 'kalan'];

    public $timestamps = true;

    protected $connection = 'best';

    public function Musterisi()
    {
        return $this->belongsTo('App\Model\Musteri', 'musteri', 'id');
    }
}
