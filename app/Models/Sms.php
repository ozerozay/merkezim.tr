<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => 'json',
        ];
    }
}
