<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Appointment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasJsonRelationships;

    protected $guarded = ['id'];

    protected $dates = ['date_start', 'date_end', 'date'];

    protected function casts(): array
    {
        return [
            'status' => 'App\AppointmentStatus',
            'service_ids' => 'json',
            'finish_service_ids' => 'json',
            'reservation_service_ids' => 'json',
            'type' => 'App\AppointmentType',
            'date_start' => 'date:Y-m-d H:i:s',
            'date_end' => 'date:Y-m-d H:i:s',
            'date' => 'date:Y-m-d',
        ];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function serviceRoom()
    {
        return $this->belongsTo(ServiceRoom::class);
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function forward_user()
    {
        return $this->belongsTo(User::class, 'forward_user_id');
    }

    public function appointmentStatuses()
    {
        return $this->hasMany(AppointmentStatuses::class);
    }

    public function finish_user()
    {
        return $this->belongsTo(User::class, 'finish_user_id');
    }
}
