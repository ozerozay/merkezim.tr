<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notlar extends Model
{
    use SoftDeletes;

    public $table = 'notlar';

    protected $fillable = ['kullanici', 'title', 'date', 'text', 'color', 'completed', 'sira', 'uzun'];

    public function getUzunAttribute($v)
    {
        if ($v == 0) {
            return false;
        }
        return true;
    }

    protected $connection = 'best';

    public function Kullanicisi()
    {
        return $this->belongsTo(User::class, 'kullanici');
    }
}
