<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientTaksitsLock extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'client_taksits_lock';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'used' => 'boolean',
            'quantity' => 'integer',
        ];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function clientTaksit()
    {
        return $this->belongsTo(ClientTaksit::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
