<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientTaksit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['date'];

    protected function casts(): array
    {
        return [
            'status' => 'App\SaleStatus',
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

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function clientTaksitsLocks()
    {
        return $this->hasMany(ClientTaksitsLock::class);
    }

    protected function dateHumanCreated(): Attribute
    {
        return Attribute::make(
            get: fn(?Carbon $value) => $this->created_at->format('d/m/Y')
        );
    }

    protected function dateHuman(): Attribute
    {
        return Attribute::make(
            get: fn(?Carbon $value) => Carbon::parse($this->date)->format(
                'd/m/Y'
            )
        );
    }
}
