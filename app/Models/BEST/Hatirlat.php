<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hatirlat extends Model
{
  use SoftDeletes;
  public $table = 'hatirlat';

  protected $casts = [
    'hatirlatilacak' => 'array',
    'icerik' => 'array'
  ];

  protected $fillable = ['kullanici', 'hatirlatilacak', 'tarih', 'icerik', 'durum'];


  protected $connection = 'best';
  public function Kullanicisi()
  {
    return $this->belongsTo('App\User', 'kullanici');
  }
}
