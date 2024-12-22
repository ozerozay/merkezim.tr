<?php

namespace App\Actions\Spotlight\Actions\Update;

use App\Actions\Spotlight\Actions\Create\CreateClientTimelineAction;
use App\ClientTimelineType;
use App\Models\Adisyon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateAdisyonAction
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
                'type' => ClientTimelineType::update_adisyon,
                'message' => $info['message'],
            ]);

            $adisyon->update([
                'date' => $info['date'],
                'staff_ids' => $info['staff_ids'],
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
