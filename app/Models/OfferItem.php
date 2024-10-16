<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $offer_id
 * @property string $itemable_type
 * @property int $itemable_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $itemable
 * @property-read \App\Models\Offer $offer
 * @method static \Database\Factories\OfferItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|OfferItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferItem whereItemableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferItem whereItemableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferItem whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfferItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function itemable()
    {
        return $this->morphTo();
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
