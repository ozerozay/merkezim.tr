<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdisyonService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'gift' => 'boolean',
        ];
    }

    public function adisyon()
    {
        return $this->belongsTo(Adisyon::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
