<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientRequest extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'type' => 'App\Enum\ClientRequestType',
            'data' => 'json',
        ];
    }

    protected $guarded = ['id'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
