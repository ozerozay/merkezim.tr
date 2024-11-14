<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agenda extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'agendas';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'time' => 'date:H:i:s',
            'end_time' => 'date:H:i:s',
            'type' => 'App\AgendaType',
            'date' => 'date:Y-m-d',
            'price' => 'float',
            'date_create' => 'date:Y-m-d',
            'status' => 'App\AgendaStatus',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function agendaOccurrence()
    {
        return $this->hasMany(AgendaOccurrence::class, 'agenda_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function talep()
    {
        return $this->belongsTo(Talep::class);
    }
}
