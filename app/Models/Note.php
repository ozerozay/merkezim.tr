<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'created_at' => 'date:Y-m-d H:i:s',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'id' => 0,
            'name' => 'SİSTEM',
        ]);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    protected function dateHuman(): Attribute
    {
        return Attribute::make(
            get: fn (?Carbon $value) => $this->created_at->format('d/m/Y')
        );
    }
}
