<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hediye extends Model
{
    use SoftDeletes;

    public $table = 'hediye';

    protected $connection = 'best';

    protected $fillable = ['sube', 'kullanici', 'atanan', 'satis', 'cesit', 'indirim', 'aktif', 'kullanilan_tarih'];
}
