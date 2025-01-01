<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SatisTipi extends Model
{
    use SoftDeletes;

    public $table = 'satis_tipi';

    protected $fillable = ['ad', 'prim', 'aktif', 'analiz', 'estetisyen'];

    protected $connection = 'best';
}
