<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
            'status' => 'App\AgendaStatus',
        ];
    }

    public function agenda()
    {
        return $this->belongsTo(Agenda::class, 'agenda_id');
    }
}
