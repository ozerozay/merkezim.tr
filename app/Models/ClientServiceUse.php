<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientServiceUse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'client_service_use';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function clientService()
    {
        return $this->belongsTo(ClientService::class);
    }

    protected function dateHumanCreated(): Attribute
    {
        return Attribute::make(
            get: fn (?Carbon $value) => $this->created_at->format('d/m/Y')
        );
    }
}
