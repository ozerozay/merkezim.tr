<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Talep extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => 'App\TalepStatus',
            'date' => 'date:Y-m-d',
            'type' => 'App\TalepType',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function talepProcesses()
    {
        return $this->hasMany(TalepProcess::class);
    }

    public function allAgenda()
    {
        return $this->hasMany(Agenda::class);
    }

    protected function dateHuman(): Attribute
    {
        return Attribute::make(
            get: fn(?Carbon $value) => Carbon::parse($this->date)->format(
                'd/m/Y'
            )
        );
    }
}
