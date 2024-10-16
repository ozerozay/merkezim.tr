<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
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
