<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Spatie\OpeningHours\OpeningHours;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'opening_hours' => 'json',
        ];
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function clientServices()
    {
        return $this->hasMany(ClientService::class);
    }

    public function clientTaksits()
    {
        return $this->hasMany(ClientTaksit::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function masrafs()
    {
        return $this->hasMany(Masraf::class);
    }

    public function kasas()
    {
        return $this->hasMany(Kasa::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function serviceRooms()
    {
        return $this->hasMany(ServiceRoom::class);
    }

    public function adisyons()
    {
        return $this->hasMany(Adisyon::class);
    }

    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function allAgenda()
    {
        return $this->hasMany(Agenda::class);
    }

    public function isOpen($date)
    {
        $openingHours = OpeningHours::create($this->opening_hours);

        return $openingHours->isOpenAt($date);
    }

    public function startTimeByDay($date)
    {
        $openingHours = OpeningHours::create($this->opening_hours);

        return $openingHours->currentOpenRange($date)->start() ?? null;
    }

    public function closeTimeByDay($date)
    {
        $openingHours = OpeningHours::create($this->opening_hours);

        return $openingHours->currentOpenRange($date)->end() ?? null;
    }

    public function branch_staff_active()
    {
        return $this->query()
            ->whereIn('id', Auth::user()->staff_branches)
            ->where('active', true)
            ->orderBy('name');
    }

    public function branch_staff_first()
    {
        try {
            $branch = $this->query()
                ->whereIn('id', Auth::user()->staff_branches)
                ->where('active', true)
                ->sole();

            return $branch->id;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
