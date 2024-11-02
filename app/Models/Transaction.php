<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['date'];

    protected function casts(): array
    {
        return [
            'type' => 'App\TransactionType',
        ];
    }

    public function kasa()
    {
        return $this->belongsTo(Kasa::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function masraf()
    {
        return $this->belongsTo(Masraf::class);
    }

    public function mahsupGiris()
    {
        return $this->hasMany(Mahsup::class, 'transaction_giris_id');
    }

    public function mahsupCikis()
    {
        return $this->hasMany(Mahsup::class, 'transaction_cikis_id');
    }

    public function transacable()
    {
        return $this->morphTo();
    }

    protected function dateHuman(): Attribute
    {
        return Attribute::make(
            get: fn (?Carbon $value) => Carbon::parse($this->date)->format('d/m/Y')
        );
    }

    protected function dateHumanCreated(): Attribute
    {
        return Attribute::make(
            get: fn (?Carbon $value) => $this->created_at->format('d/m/Y  H:i:s')
        );
    }
}
