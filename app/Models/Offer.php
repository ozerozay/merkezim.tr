<?php

namespace App\Models;

use App\Enum\PaymentStatus;
use App\OfferStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['expire_date'];

    protected function casts(): array
    {
        return [
            'status' => 'App\OfferStatus',
            'expire_date' => 'date:Y-m-d',
            'created_at' => 'date:Y-m-d H:i:s',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'id' => 0,
            'name' => 'SİSTEM',
        ]);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(OfferItem::class, 'offer_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transacable');
    }

    public function clientServices()
    {
        return $this->hasMany(ClientService::class);
    }

    protected function dateHumanCreated(): Attribute
    {
        return Attribute::make(
            get: fn (?Carbon $value) => $this->created_at->format('d/m/Y')
        );
    }

    public function remainingDay()
    {
        return $this->expire_date == null ? 'SÜRESİZ' : ceil(Carbon::now()->diffInDays($this->expire_date));
    }

    protected function dateHumanExpire(): Attribute
    {
        return Attribute::make(
            get: fn (?Carbon $value) => $this->expire_date == null
                ? 'SÜRESİZ'
                : Carbon::parse($this->expire_date)->format('d/m/Y')
        );
    }

    public function isCompleted(): bool
    {
        return $this->status == OfferStatus::success;
    }

    // Ödemesi olup olmadığını kontrol eden fonksiyon
    public function hasPayment($status = [PaymentStatus::pending->name]): bool
    {
        return \App\Models\Payment::whereJsonContains('data->data->offer_id', $this->id)->whereIn('status', $status)->exists();
    }

    public function hasPaymentActive(): bool
    {
        return \App\Models\Payment::query()
            ->whereJsonContains('data->data->offer_id', $this->id)
            ->whereIn('status', [\App\Enum\PaymentStatus::approved->name, \App\Enum\PaymentStatus::pending->name, \App\Enum\PaymentStatus::success->name])
            ->exists();
    }

    // Ödemeleri getiren fonksiyon (isteğe bağlı)
    public function payments()
    {
        return \App\Models\Payment::where('data->offer_id', $this->id)->get();
    }
}
