<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Casts\Json;

class Dosya extends Model
{
    use SoftDeletes;

    public $table = 'dosya';

    protected $connection = 'best';

    protected $fillable = ['kullanici', 'dosya', 'detay'];

    protected $casts = [
        'detay' => 'array',
    ];

    public function Kullanicisi()
    {
        return $this->belongsTo('App\User', 'kullanici', 'id');
    }
}
