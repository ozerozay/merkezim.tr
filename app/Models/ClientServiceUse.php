<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
