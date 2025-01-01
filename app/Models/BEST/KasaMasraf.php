<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KasaMasraf extends Model
{
    use SoftDeletes;

    public $table = 'kasa_masraf';

    protected $fillable = ['ad', 'aktif'];

    protected $connection = 'best';
}
