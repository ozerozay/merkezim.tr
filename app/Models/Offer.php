<?php

namespace App\Models;

use App\OfferStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property string $unique_id
 * @property int|null $user_id
 * @property int $client_id
 * @property string|null $expire_date
 * @property string $price
 * @property OfferStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OfferItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\OfferFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereExpireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUniqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer withoutTrashed()
 * @mixin \Eloquent
 */
class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['expire_date'];

    protected function casts(): array
    {
        return [
            'status' => OfferStatus::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(OfferItem::class, 'offer_id');
    }
}
