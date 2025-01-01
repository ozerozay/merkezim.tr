<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;

class SMSSablon extends Model
{
    public $table = 'sms_sablon';

    protected $fillable = ['mesaj', 'baslik'];

    public $timestamps = false;

    protected $connection = 'best';
}
