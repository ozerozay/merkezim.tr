<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hizmet extends Model
{
    use SoftDeletes;

    public $table = 'hizmet';

    protected $connection = 'best';

    protected $fillable = [
        'ad', 'sure', 'aktif', 'cesit', 'fiyat', 'fiyat_senet'
    ];

    public $timestamps = true;
}
