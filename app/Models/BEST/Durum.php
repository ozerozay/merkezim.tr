<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Durum extends Model
{
  use SoftDeletes, HasJsonRelationships;
  public $table = 'durum';

  protected $fillable = ['ad', 'aktif', 'ozellik'];

  protected $connection = 'best';

  protected $casts = [
    'ozellik' => 'array'
  ];
}
