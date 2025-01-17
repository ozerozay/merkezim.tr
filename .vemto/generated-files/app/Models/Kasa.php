<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kasa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => 'boolean',
            'active' => 'boolean',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function girisMahsups()
    {
        return $this->hasMany(Mahsup::class, 'giris_kasa_id');
    }

    public function cikisMahsups()
    {
        return $this->hasMany(Mahsup::class, 'cikis_kasa_id');
    }
}
