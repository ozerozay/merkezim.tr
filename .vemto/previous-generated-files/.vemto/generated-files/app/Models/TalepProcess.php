<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TalepProcess extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => 'App\TalepStatus',
            'date' => 'date:Y-m-d',
        ];
    }

    public function talep()
    {
        return $this->belongsTo(Talep::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
