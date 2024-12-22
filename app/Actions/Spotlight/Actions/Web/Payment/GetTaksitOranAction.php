<?php

namespace App\Actions\Spotlight\Actions\Web\Payment;

use Lorisleiva\Actions\Concerns\AsAction;

class GetTaksitOranAction
{
    use AsAction;

    public function handle($brand, $branch)
    {
        try {
            $taksit_oran = \App\Models\TaksitOran::where('branch_id', $branch)->first();

            if (! $taksit_oran) {
                return collect();
            }

            if (! isset($taksit_oran['data'])) {
                return collect();
            }

            if (! isset($taksit_oran['data'][$brand])) {
                return collect();
            }

            return collect($taksit_oran['data'][$brand]);

        } catch (\Throwable $e) {
            dump($e);

            return collect();
        }
    }
}
