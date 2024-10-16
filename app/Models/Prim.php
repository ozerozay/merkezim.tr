<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prim extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'prim';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleType()
    {
        return $this->belongsTo(SaleType::class);
    }

    public function staff_muhasebe()
    {
        return $this->morphOne(StaffMuhasebe::class, 'muhasebe');
    }
}
