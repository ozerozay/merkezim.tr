<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusteriSozlesme extends Model
{
    use SoftDeletes;

    public $table = 'musteri_sozlesme';

    protected $connection = 'best';

    protected $fillable = ['musteri', 'satis', 'hizmet', 'senet'];

    protected $casts = [
        'satis' => 'array',
        'hizmet' => 'array',
        'senet' => 'array'
    ];
}
