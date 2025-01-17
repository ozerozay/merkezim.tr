<?php

namespace App;

use App\Actions\Spotlight\Actions\User\CheckBranchPermission;
use App\Actions\Spotlight\Actions\User\CheckUserInstantApprove;
use App\Actions\Spotlight\Actions\User\CheckUserPermission;
use App\Actions\Spotlight\Actions\User\RequestApproveAction;
use App\Models\Coupon;
use App\Models\Offer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Mary\Exceptions\ToastException;

class Peren
{
    public static string $approve_request_ok = 'Talebiniz alındı, onaylandıktan sonra aktif edilecektir.';

    public static function parseDate($date): ?string
    {
        return $date != null ? Carbon::parse($date)->format('Y-m-d') : null;
    }

    public static function parseDateField($date): ?string
    {
        return $date != null ? Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d') : null;
    }

    public static function parseDateAndFormat($date): ?string
    {
        return $date != null ? Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y') : null;
    }

    public static function formatRangeDate($date): array
    {
        $first_date = null;
        $last_date = null;
        $split_date = preg_split('/\s-\s/', $date);
        if (count($split_date) > 1) {
            $first_date = Carbon::createFromFormat('Y-m-d H:i', $split_date[0])->format('Y-m-d');
            $last_date = Carbon::createFromFormat('Y-m-d H:i', $split_date[1])->format('Y-m-d');
        } else {
            $first_date = $last_date = Carbon::createFromFormat('Y-m-d H:i', $split_date[0])->format('Y-m-d');
        }

        return [
            'first_date' => $first_date,
            'last_date' => $last_date,
        ];
    }

    public static function dateConfig($min = null, $max = null, $enableTime = false, $mode = null, $timeOnly = false): array
    {
        $config = ['altFormat' => 'd/m/Y', 'locale' => 'tr', 'disableMobile' => true];

        if ($enableTime) {
            $config['enableTime'] = true;
            $config['altFormat'] = 'd/m/Y H:i';
            if ($timeOnly) {
                $config['noCalendar'] = true;
                $config['altFormat'] = 'H:i';
            }
        }

        if ($mode) {
            $config['mode'] = $mode;
        }

        if ($min) {
            $config['minDate'] = $min;
        }

        if ($max) {
            $config['maxDate'] = $max;
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
        return $collection->filter(fn($item) => Str::contains(Str::upper($item[$key]), Str::upper($term)));
    }

    /**
     * @throws ToastException
     */
    public static function runDatabaseTransaction(callable $callback)
    {
        try {
            \DB::beginTransaction();

            return $callback();
        } catch (\Throwable $e) {
            \DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }

    public static function runDatabaseTransactionApprove($info, callable $callback, $approve = false)
    {
        try {
            \DB::beginTransaction();
            CheckUserPermission::run($info['permission']);
            CheckBranchPermission::run($info['client_id'] ?? null);

            if ($approve || CheckUserInstantApprove::run($info['user_id'], $info['permission'])) {
                return $callback();
            } else {
                RequestApproveAction::run($info, $info['user_id'], $info['permission'], $info['message'] ?? '');
            }

            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }

    public static function pluckNames($data)
    {
        return $data->map(fn($d) => $d->name)->implode(', ');
    }
}
