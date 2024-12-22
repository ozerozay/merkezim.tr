<?php

namespace App\Actions\Spotlight\Actions\Talep;

use App\AgendaStatus;
use App\AgendaType;
use App\Models\Agenda;
use App\Models\Talep;
use App\Models\TalepProcess;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTalepAppointmentAction
{
    use AsAction;

    public function handle($info)
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            TalepProcess::create($info);

            Talep::where('id', $info['talep_id'])->update([
                'status' => $info['status'],
            ]);

            $talep = Talep::find($info['talep_id']);
            $agenda = Agenda::where('talep_id', $info['talep_id'])->first();
            if ($agenda) {
                $agenda->agendaOccurrence()->delete();
                $agenda->delete();
            }

            $agenda = Agenda::create([
                'talep_id' => $info['talep_id'],
                'user_id' => $info['user_id'],
                'name' => $talep->name,
                'message' => $info['message'],
                'date' => $info['date'],
                'date_create' => $info['date'],
                'time' => $info['date'],
                'branch_id' => $talep->branch_id,
                'type' => AgendaType::appointment,
            ]);

            if ($agenda) {
                $agenda->agendaOccurrence()->create([
                    'occurrence_date' => $agenda->date,
                    'status' => AgendaStatus::waiting,
                ]);
            }

            \DB::commit();
        });
    }
}
