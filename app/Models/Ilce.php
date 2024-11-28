<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ilce extends Model
{
    protected $guarded = ['id'];

    protected $table = 'ilceler';

    public function il()
    {
        return $this->belongsTo(Il::class, 'il_id');
    }
}
