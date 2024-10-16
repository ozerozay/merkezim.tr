<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffMuhasebe extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'staff_muhasebe';

    protected $guarded = ['id'];

    protected $dates = ['date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
