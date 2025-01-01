<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tedarikci extends Model
{
    use SoftDeletes;

    public $table = 'tedarikci';

    protected $fillable = ['sube', 'ad', 'aktif'];

    public function Subesi()
    {
        return $this->belongsTo('App\Model\Sube', 'sube');
    }

    protected $connection = 'best';

    public function Muhasebesi()
    {
        return $this->hasMany('App\Model\TedarikciMuhasebe', 'tedarikci');
    }
}
