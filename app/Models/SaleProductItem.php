<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleProductItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function saleProduct()
    {
        return $this->belongsTo(SaleProduct::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
