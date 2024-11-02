<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class User extends Authenticatable
{
    use HasRoles;
    use HasFactory;
    use Notifiable;
    use HasJsonRelationships;

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
            'labels' => 'json',
            'instant_approve' => 'boolean',
        ];
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
     * Get the client_branch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client_branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get all of the notes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
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
     * Get all of the coupons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
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
     * Get all of the userService.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userService()
    {
        return $this->hasMany(ClientService::class, 'user_id');
    }

    /**
     * Get all of the serviceUses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceUses()
    {
        return $this->hasMany(ClientServiceUse::class, 'user_id');
    }

    /**
     * Get all of the clientServiceUses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientServiceUses()
    {
        return $this->hasMany(ClientServiceUse::class, 'client_id');
    }

    /**
     * Get all of the approves.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function approves()
    {
        return $this->hasMany(Approve::class);
    }

    /**
     * Get all of the approved_by.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function approved_by()
    {
        return $this->hasMany(Approve::class, 'approved_by');
    }

    /**
     * Get all of the adisyons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adisyons()
    {
        return $this->hasMany(Adisyon::class);
    }

    /**
     * Get all of the clientAdisyons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientAdisyons()
    {
        return $this->hasMany(Adisyon::class, 'client_id');
    }

    /**
     * Get all of the saleProducts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class);
    }

    /**
     * Get all of the clientSaleProducts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientSaleProducts()
    {
        return $this->hasMany(SaleProduct::class, 'client_id');
    }

    /**
     * Get all of the mahsups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mahsups()
    {
        return $this->hasMany(Mahsup::class);
    }

    /**
     * Get all of the clientTaksitsLocks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientTaksitsLocks()
    {
        return $this->hasMany(ClientTaksitsLock::class, 'client_id');
    }

    /**
     * Get all of the clientAppointments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientAppointments()
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    /**
     * Get all of the appointmentsForward.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointmentsForward()
    {
        return $this->hasMany(Appointment::class, 'forward_user_id');
    }

    /**
     * Get all of the appointmentStatuses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointmentStatuses()
    {
        return $this->hasMany(AppointmentStatuses::class);
    }

    /**
     * Get all of the appointmentsFinish.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointmentsFinish()
    {
        return $this->hasMany(Appointment::class, 'finish_user_id');
    }

    public function search()
    {
    }

    public function staff_branch()
    {
        return $this->belongsToJson(Branch::class, 'staff_branches');
    }

    public function client_labels()
    {
        return $this->belongsToJson(Label::class, 'labels');
    }
}
