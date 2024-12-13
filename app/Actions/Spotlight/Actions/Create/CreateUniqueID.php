<?php

namespace App\Actions\Spotlight\Actions\Create;

use App\Exceptions\AppException;
use App\Models\Adisyon;
use App\Models\Offer;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\ShopPackage;
use App\Models\ShopService;
use App\Models\User;
use App\Models\UserReport;
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
        } elseif ($type == 'sale_product') {
            do {
                $code = random_int(100000000, 999999999);
            } while (SaleProduct::select('unique_id')->where('unique_id', '=', $code)->exists());

            return $code;
        } elseif ($type == 'sale') {
            do {
                $code = random_int(100000000, 999999999);
            } while (Sale::select('unique_id')->where('unique_id', '=', $code)->exists());

            return $code;
        } elseif ($type == 'shop_package') {
            do {
                $code = random_int(100000000, 999999999);
            } while (ShopPackage::select('unique_id')->where('unique_id', '=', $code)->exists());

            return $code;
        } elseif ($type == 'shop_service') {
            do {
                $code = random_int(100000000, 999999999);
            } while (ShopService::select('unique_id')->where('unique_id', '=', $code)->exists());

            return $code;
        } elseif ($type == 'report') {
            do {
                $code = random_int(100000000, 999999999);
            } while (UserReport::select('unique_id')->where('unique_id', '=', $code)->exists());

            return $code;
        } else {
            throw new AppException('Çeşit bulunamadı.');
        }
    }
}
