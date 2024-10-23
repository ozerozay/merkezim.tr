<?php

namespace App\Actions\Helper;

use App\Models\Adisyon;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateAdisyonUniqueID
{
    use AsAction;

    public function handle()
    {
        do {
            $code = random_int(100000000, 999999999);
        } while (Adisyon::select('unique_id')->where('unique_id', '=', $code)->exists());

        return $code;
    }
}
