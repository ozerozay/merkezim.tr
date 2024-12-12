<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopPackage extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'buy_time' => 'integer',
            'month' => 'integer',
            'kdv' => 'integer',
            'price' => 'float',
        ];
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
