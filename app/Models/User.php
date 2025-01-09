<?php

namespace App\Models;

use App\OfferStatus;
use App\SaleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Namu\WireChat\Traits\Chatable;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class User extends Authenticatable
{
    use Chatable;
    use HasFactory;
    use HasJsonRelationships;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

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
            'can_login' => 'boolean',
            'send_sms' => 'boolean',
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

    public function clientOffers()
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

    /**
     * Get all of the allAgenda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allAgenda()
    {
        return $this->hasMany(Agenda::class);
    }

    /**
     * Get all of the clientAgenda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientAgenda()
    {
        return $this->hasMany(Agenda::class, 'client_id');
    }

    /**
     * Get all of the clientTimelines.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientTimelines()
    {
        return $this->hasMany(ClientTimeline::class);
    }

    /**
     * Get all of the clientClientTimelines.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientClientTimelines()
    {
        return $this->hasMany(ClientTimeline::class, 'client_id');
    }

    public function adminHomeWidgets(): HasMany
    {
        return $this->hasMany(AdminHomeWidget::class, 'user_id');
    }

    /**
     * Get all of the taleps.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taleps()
    {
        return $this->hasMany(Talep::class);
    }

    public function clientReferans()
    {
        return $this->hasMany(User::class, 'referans_id');
    }

    /**
     * Get all of the talepProcesses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function talepProcesses()
    {
        return $this->hasMany(TalepProcess::class);
    }

    public function search() {}

    public function staff_branch()
    {
        return $this->belongsToJson(Branch::class, 'staff_branches');
    }

    public function client_labels()
    {
        return $this->belongsToJson(Label::class, 'labels');
    }

    public function staffInstantApproves()
    {
        return $this->belongsToJson(Permission::class, 'instant_approves');
    }

    public function label_names()
    {
        return $this->client_labels()
            ->pluck('name')
            ->implode(', ');
    }

    public function branch_names()
    {
        return $this->staff_branch()
            ->pluck('name')
            ->implode(', ');
    }

    public function totalDebt()
    {
        return $this->clientTaksits()
            ->where('status', SaleStatus::success)
            ->sum('remaining');
    }

    public function totalLateDebt()
    {
        return $this->clientTaksits()
            ->whereDate('date', '<=', date('Y-m-d'))
            ->where('status', SaleStatus::success)
            ->sum('remaining');
    }

    public function hasDelayedPayment(): bool
    {
        return $this->clientTaksits()
            ->whereDate('date', '<=', date('Y-m-d'))
            ->where('status', SaleStatus::success)
            ->where('remaining', '>', 0)
            ->exists();
    }

    public function hasActiveOffer(): bool
    {
        return $this->offers()
            ->where('status', OfferStatus::waiting)
            ->where(function ($query) {
                $query->whereNull('expire_date')
                    ->orWhere('expire_date', '>=', date('Y-m-d'));
            })
            ->exists();
    }

    public function canCreateChats(): bool
    {
        return true;
    }
}
