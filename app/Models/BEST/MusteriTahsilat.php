<?php

namespace App\Models\BEST;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusteriTahsilat extends Model
{
    use SoftDeletes;

    protected $table = 'musteri_tahsilat';

    protected $connection = 'best';
}
