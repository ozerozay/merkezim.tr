<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminHomeWidget extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'visible',
        'order'
    ];

    protected $casts = [
        'visible' => 'boolean'
    ];
}
