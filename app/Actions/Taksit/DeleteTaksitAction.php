<?php

namespace App\Actions\Taksit;

use App\ClientTimelineType;
use App\Exceptions\AppException;
use App\Models\ClientTaksit;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class DeleteTaksitAction
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
                'type' => ClientTimelineType::update_taksit_date,
                'message' => $taksit->total.' TLlik taksit silindi. '.$info['message'],
            ]);

            $taksit->delete();

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
