<?php

namespace App\Models;

use App\Peren;
use App\Traits\StringHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class User extends Authenticatable
{
    use HasFactory;
    use HasJsonRelationships;
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
            'gender' => 'boolean',
            'active' => 'boolean',
            'first_login' => 'boolean',
            'staff_branches' => 'json',
        ];
    }

    /**
     * Get all of the client_notes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function client_notes()
    {
        return $this->hasMany(Note::class, 'client_id');
    }

    /**
     * Get all of the client_coupons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function client_coupons()
    {
        return $this->hasMany(Coupon::class, 'client_id');
    }

    /**
     * Get all of the offers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Get all of the client_offers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function client_offers()
    {
        return $this->hasMany(Offer::class, 'client_id');
    }

    /**
     * Get all of the sales.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get all of the clientServices.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientServices()
    {
        return $this->hasMany(ClientService::class, 'client_id');
    }

    /**
     * Get all of the clientTaksits.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientTaksits()
    {
        return $this->hasMany(ClientTaksit::class, 'client_id');
    }

    /**
     * Get all of the client_sales.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function client_sales()
    {
        return $this->hasMany(Sale::class, 'client_id');
    }

    /**
     * Get all of the transactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all of the client_transactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function client_transactions()
    {
        return $this->hasMany(Transaction::class, 'client_id');
    }

    /**
     * Get all of the prims.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prims()
    {
        return $this->hasMany(Prim::class);
    }

    /**
     * Get all of the staffMuhasebes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staffMuhasebes()
    {
        return $this->hasMany(StaffMuhasebe::class);
    }

    /**
     * Get all of the clientService.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientService()
    {
        return $this->hasMany(ClientService::class, 'user_id');
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->name = StringHelper::strUpper($user->name);
            $user->unique_id = Peren::unique_user_number();
            $user->birth_date = Peren::parseDate($user->birth_date);
        });

        static::updating(function (User $user) {
            $user->name = StringHelper::strUpper($user->name);
            $user->birth_date = Peren::parseDate($user->birth_date);
        });
    }

    public function search() {}

    public function staff_branch()
    {
        return $this->belongsToJson(Branch::class, 'staff_branches');
    }

    public function client_branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
