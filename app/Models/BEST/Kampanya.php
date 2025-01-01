<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class Kampanya extends Model
{

    public $table = 'kampanya';

    protected $fillable = ['kod', 'talep', 'durum'];


    public function Talebi()
    {
        return $this->belongsTo('App\Model\Talep', 'talep');
    }

    protected $connection = 'best';
}
