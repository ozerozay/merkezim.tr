<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Appointment extends Model
{
    use HasFactory;
    use HasJsonRelationships;
    use SoftDeletes;

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

    public function services()
    {
        return $this->belongsToJson(ClientService::class, 'service_ids');
    }

    public function finish_services()
    {
        return $this->belongsToJson(ClientService::class, 'finish_service_ids');
    }

    protected function serviceNames(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->services->map(fn ($service) => $service->service->name.'(1)')->implode(', ')
        );
    }

    protected function finishServiceNames(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->finish_services->map(fn ($service) => $service->service->name.'(1)')->implode(', ')
        );
    }

    public function service_names(Appointment $appointment)
    {
        return $appointment->services->map(fn ($service) => $service->service->name.'(1)')->implode(', ');
    }

    public function service_names_public(Appointment $appointment)
    {
        return $appointment->services->map(function ($service) {
            if ($service->service->is_visible && $service->service->active) {
                return $service->service->name;
            }

            return null;
        })->implode(', ');
    }

    public function finish_service_names_public(Appointment $appointment)
    {
        return $appointment->finish_services->map(function ($service) {
            if ($service->service->is_visible && $service->service->active) {
                return $service->service->name;
            }

            return null;
        })->implode(', ');
    }

    protected function dateHuman(): Attribute
    {
        return Attribute::make(
            get: fn (?Carbon $value) => Carbon::parse($this->date)->format('d/m/Y')
        );
    }

    protected function dateHumanStart(): Attribute
    {
        return Attribute::make(
            get: fn (?Carbon $value) => Carbon::parse($this->date_start)->format('H:i')
        );
    }

    protected function dateHumanEnd(): Attribute
    {
        return Attribute::make(
            get: fn (?Carbon $value) => Carbon::parse($this->date_end)->format('H:i')
        );
    }
}
