<?php

namespace App\Actions\Spotlight\Actions\Update;

use App\ClientTimelineType;
use App\Models\ClientTaksit;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateClientTaksitDateAction
{
    use AsAction;

    public function handle($info)
    {
        try {
            DB::beginTransaction();

            $taksit = ClientTaksit::where('id', $info['id'])->first();

            \App\Actions\Client\CreateClientTimelineAction::run([
                'client_id' => $taksit->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::update_taksit_date,
                'message' => $taksit->total.'TL '.$taksit->date.' taksidin tarihi '.$info['date'].' olarak değiştirildi. '.$info['message'],
            ]);

            $taksit->date = $info['date'];
            $taksit->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
