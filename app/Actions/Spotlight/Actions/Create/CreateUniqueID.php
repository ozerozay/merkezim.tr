<?php

namespace App\Actions\Spotlight\Actions\Create;

use App\Exceptions\AppException;
use App\Models\Adisyon;
use App\Models\Offer;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use Random\RandomException;

class CreateUniqueID
{
    use AsAction;

    /**
     * @throws AppException
     * @throws RandomException
     */
    public function handle($type)
    {
        if ($type == 'user') {
            do {
                $code = random_int(100000000, 999999999);
            } while (User::select('unique_id')->where('unique_id', '=', $code)->exists());

            return $code;
        } elseif ($type == 'offer') {
            do {
                $code = random_int(100000000, 999999999);
            } while (Offer::select('unique_id')->where('unique_id', '=', $code)->exists());

            return $code;
        } elseif ($type == 'adisyon') {
            do {
                $code = random_int(100000000, 999999999);
            } while (Adisyon::select('unique_id')->where('unique_id', '=', $code)->exists());

            return $code;
        } else {
            throw new AppException('Çeşit bulunamadı.');
        }
    }
}
