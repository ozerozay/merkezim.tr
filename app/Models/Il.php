<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Il extends Model
{
    protected $guarded = ['id'];

    protected $table = 'iller';

    public function ilces()
    {
        return $this->hasMany(Ilce::class, 'il_id');
    }
}
