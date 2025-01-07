<?php

namespace App\Models;

use App\Enum\WebFormType;
use App\Enum\WebFormStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebForm extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'branch_id',
        'type',
        'data',
        'status',
        'processed_by',
        'processed_at',
        'process_note',
    ];

    protected $casts = [
        'type' => WebFormType::class,
        'status' => WebFormStatus::class,
        'data' => 'json',
        'processed_at' => 'datetime',
    ];

    // İlişkiler
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scope'lar
    public function scopePending($query)
    {
        return $query->where('status', WebFormStatus::PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', WebFormStatus::APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', WebFormStatus::REJECTED);
    }

    // Helper metodlar
    public function approve(string $note = null): void
    {
        $this->update([
            'status' => WebFormStatus::APPROVED,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'process_note' => $note,
        ]);
    }

    public function reject(string $note): void
    {
        $this->update([
            'status' => WebFormStatus::REJECTED,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'process_note' => $note,
        ]);
    }
}
