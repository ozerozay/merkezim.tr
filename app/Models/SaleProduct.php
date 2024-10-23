<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleProduct extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sale_product';

    protected $guarded = ['id'];

    protected $dates = ['date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function saleProductItems()
    {
        return $this->hasMany(SaleProductItem::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transacable');
    }

    public function staffs()
    {
        return $this->belongsToJson(User::class, 'staff_ids');
    }
}
