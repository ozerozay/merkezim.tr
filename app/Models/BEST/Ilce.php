<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class Ilce extends Model
{
    public $table = 'ilce';

    protected $fillable = ['il', 'ilce'];

    public $timestamps = false;

    protected $connection = 'best';
}
