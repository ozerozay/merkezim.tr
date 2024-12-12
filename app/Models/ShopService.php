<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopService extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'buy_max' => 'integer',
            'buy_min' => 'integer',
            'kdv' => 'integer',
            'price' => 'float',
        ];
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
