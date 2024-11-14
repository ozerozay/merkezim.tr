<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TalepProcess extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => 'App\TalepStatus',
            'date' => 'date:Y-m-d',
            'created_at' => 'date:Y-m-d H:i:s',
        ];
    }

    public function talep()
    {
        return $this->belongsTo(Talep::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function dateHuman(): Attribute
    {
        return Attribute::make(
            get: fn (?Carbon $value) => Carbon::parse($this->created_at)->format('d/m/Y H:i:s')
        );
    }
}
