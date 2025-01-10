<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Namu\WireChat\Models\Message as BaseMessage;
use Illuminate\Broadcasting\PrivateChannel;

class Message extends BaseMessage
{
    use BelongsToTenant, HasUuids;

    protected $fillable = [
        'chat_id',
        'user_id',
        'tenant_id',
        'content',
        'type',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function broadcastOn()
    {
        return new PrivateChannel("chat.{$this->chat_id}");
    }
}
