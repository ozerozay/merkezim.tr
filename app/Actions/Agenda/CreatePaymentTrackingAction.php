<?php

namespace App\Actions\Agenda;

use App\Exceptions\AppException;
use App\Models\Agenda;
use App\Models\AgendaOccurrence;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreatePaymentTrackingAction
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            $agenda = Agenda::create($info);

            dump($info);
            if ($info['frequency'] == 'tek') {
                AgendaOccurrence::create([
                    'agenda_id' => $agenda->id,
                    'occurrence_date' => $info['date'],
                ]);
            } elseif ($info['frequency'] == 'gun') {
                $startDate = Carbon::createFromFormat('Y-m-d', $info['date']);
                for ($i = 0; $i < $info['installment']; $i++) {
                    AgendaOccurrence::create([
                        'agenda_id' => $agenda->id,
                        'occurrence_date' => $startDate->toDateString(),
                    ]);
                    $startDate->addDay();
                }
            } elseif ($info['frequency'] == 'hafta') {
                $startDate = Carbon::createFromFormat('Y-m-d', $info['date']);
                for ($i = 0; $i < $info['installment']; $i++) {
                    AgendaOccurrence::create([
                        'agenda_id' => $agenda->id,
                        'occurrence_date' => $startDate->toDateString(),
                    ]);
                    $startDate->addDays(7);
                }
            } elseif ($info['frequency'] == 'ay') {
                $startDate = Carbon::createFromFormat('Y-m-d', $info['date']);
                for ($i = 0; $i < $info['installment']; $i++) {
                    AgendaOccurrence::create([
                        'agenda_id' => $agenda->id,
                        'occurrence_date' => $startDate->toDateString(),
                    ]);
                    $startDate->addMonth();
                }
            }

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
