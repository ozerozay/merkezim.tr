<?php

namespace App\Actions\Agenda;

use App\Exceptions\AppException;
use App\Models\Agenda;
use App\Models\AgendaOccurrence;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateReminderAction
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            $agenda = Agenda::create($info);

            AgendaOccurrence::create([
                'agenda_id' => $agenda->id,
                'occurrence_date' => $info['date'],
            ]);

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
