<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'name' => 'string',
        ];
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function prims()
    {
        return $this->hasMany(Prim::class);
    }
}
