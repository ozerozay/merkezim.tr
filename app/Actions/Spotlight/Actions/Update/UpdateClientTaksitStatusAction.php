<?php

namespace App\Actions\Spotlight\Actions\Update;

use App\Actions\Spotlight\Actions\Create\CreateClientTimelineAction;
use App\ClientTimelineType;
use App\Models\ClientTaksit;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateClientTaksitStatusAction
{
    use AsAction;

    /**
     * @throws ToastException
     */
    public function handle($info)
    {
        try {

            DB::beginTransaction();

            $client_taksit = ClientTaksit::where('id', $info['id'])->first();

            CreateClientTimelineAction::run([
                'client_id' => $client_taksit->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::update_taksit_status,
                'message' => $client_taksit->date.' tarihli taksit durumu düzenlendi.',
            ]);

            $client_taksit->update([
                'status' => $info['status'],
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
