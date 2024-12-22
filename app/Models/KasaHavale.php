<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasaHavale extends Model
{
    protected $guarded = ['*'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function kasa()
    {
        return $this->belongsTo(Kasa::class, 'kasa_id');
    }
}
