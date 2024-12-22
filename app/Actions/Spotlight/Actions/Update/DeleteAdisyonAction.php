<?php

namespace App\Actions\Spotlight\Actions\Update;

use App\Actions\Spotlight\Actions\Create\CreateClientTimelineAction;
use App\ClientTimelineType;
use App\Models\Adisyon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class DeleteAdisyonAction
{
    use AsAction;

    public function handle($info): void
    {
        try {

            DB::beginTransaction();

            $adisyon = Adisyon::where('id', $info['id'])->first();

            CreateClientTimelineAction::run([
                'client_id' => $adisyon->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::delete_adisyon,
                'message' => $info['message'],
            ]);

            foreach ($adisyon->adisyonProducts() as $item) {
                $item->product()->increment('stok', $item->total);
            }

            $adisyon->adisyonProducts()->delete();
            $adisyon->transactions()->delete();
            $adisyon->adisyonServices()->delete();
            $adisyon->adisyonPackages()->delete();

            $adisyon->delete();

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
