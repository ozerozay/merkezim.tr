<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaOccurrence extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['occurrence_date'];

    protected function casts(): array
    {
        return [
            'occurrence_date' => 'date:Y-m-d',
        ];
    }

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }
}
