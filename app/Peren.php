<?php

namespace App;

use App\Models\Coupon;
use App\Models\Offer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Peren
{
    public static string $approve_request_ok = 'Talebiniz alındı, onaylandıktan sonra aktif edilecektir.';

    public static function parseDate($date)
    {
        return $date != null ? Carbon::parse($date)->format('Y-m-d') : null;
    }

    public static function parseDateField($date)
    {
        return $date != null ? Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d') : null;
    }

    public static function dateConfig($min)
    {
        $config = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

        if ($min) {
            $config['minDate'] = $min;
        }

        return $config;
    }

    public static function opening_hours()
    {
        return [
            'monday' => ['11:00-19:30'],
            'tuesday' => ['11:00-19:30'],
            'wednesday' => ['11:00-19:30'],
            'thursday' => ['11:00-19:30'],
            'friday' => ['11:00-19:30'],
            'saturday' => ['11:00-19:30'],
            'sunday' => [],
            'exceptions' => [],
        ];
    }

    public static function unique_user_number()
    {
        do {
            $code = random_int(100000000, 999999999);
        } while (User::select('unique_id')->where('unique_id', '=', $code)->exists());

        return $code;
    }

    public static function unique_coupon_code()
    {
        do {
            $code = random_int(100000000, 999999999);
        } while (Coupon::select('code')->where('code', '=', $code)->exists());

        return $code;
    }

    public static function unique_offer_code()
    {
        do {
            $code = random_int(100000000, 999999999);
        } while (Offer::select('unique_id')->where('unique_id', '=', $code)->exists());

        return $code;
    }

    public static function whereLike($collection, $key, $term): Collection
    {
        return $collection->filter(fn ($item) => Str::contains(Str::upper($item[$key]), Str::upper($term)));
    }
}
