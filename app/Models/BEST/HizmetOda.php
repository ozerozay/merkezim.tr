<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HizmetOda extends Model
{
    use SoftDeletes;

    public $table = 'hizmet_oda';

    protected $fillable = [
        'sube', 'ad', 'aktif'
    ];

    protected $connection = 'best';

    public $timestamps = true;

    public function Subesi()
    {
        return $this->belongsTo(Sube::class, 'sube');
    }
}
