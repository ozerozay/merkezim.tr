<?php

namespace App\Actions\Spotlight\Actions\Update;

use App\ClientTimelineType;
use App\Models\ClientTaksit;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class DeleteClientTaksitAction
{
    use AsAction;

    public function handle($info)
    {
        try {
            DB::beginTransaction();

            $taksit = ClientTaksit::find($info['id']);

            \App\Actions\Client\CreateClientTimelineAction::run([
                'client_id' => $taksit->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::delete_taksit,
                'message' => $taksit->total.' TLlik taksit silindi. '.$info['message'],
            ]);

            $taksit->clientTaksitsLocks()->delete();

            $taksit->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
