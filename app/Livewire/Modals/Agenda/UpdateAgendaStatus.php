<?php

namespace App\Livewire\Modals\Agenda;

use App\AgendaStatus;
use App\Models\Agenda;
use App\Models\AgendaOccurrence;
use App\Models\Talep;
use App\Rules\PriceValidation;
use App\TalepStatus;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class UpdateAgendaStatus extends SlideOver
{
    use Toast;

    public int|AgendaOccurrence $occurrence;

    public $status;

    public $price;

    public $date;

    public $name;

    public $message;

    public function mount(AgendaOccurrence $occurrence): void
    {
        $this->occurrence = $occurrence->load('agenda');
        $this->fill($this->occurrence->agenda);
        $this->date = $this->occurrence->occurrence_date->format('Y-m-d');
        $this->status = $this->occurrence->status->name;
    }

    public function save()
    {
        try {
            $validator = \Validator::make([
                'status' => $this->status,
                'price' => $this->price,
                'date' => $this->date,
                'name' => $this->name,
                'message' => $this->message,
            ], [
                'status' => 'required',
                'price' => ['nullable', new PriceValidation],
                'date' => ['nullable', 'date'],
                'name' => 'required',
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            $agenda = Agenda::find($this->occurrence->agenda_id);
            $agenda->name = $this->name;
            $agenda->status = $this->status;
            $agenda->message = $this->message;
            $agenda->price = $this->price;
            $agenda->date = $this->date;
            $agenda->save();

            $this->occurrence->occurrence_date = $this->date;
            $this->occurrence->status = $this->status;
            $this->occurrence->save();

            if ($agenda->talep) {
                if ($agenda->status == AgendaStatus::error) {
                    $talep = Talep::where('id', $agenda->talep_id)->first();
                    $talep->talepProcesses()->create([
                        'status' => TalepStatus::iptal,
                        'message' => 'Randevu iptal edildi.',
                        'user_id' => auth()->user()->id,
                    ]);
                    $talep->status = TalepStatus::iptal;
                    $talep->save();
                } elseif ($agenda->status == AgendaStatus::success) {
                    $talep = Talep::where('id', $agenda->talep_id)->first();
                    $talep->talepProcesses()->create([
                        'status' => TalepStatus::satis,
                        'message' => 'Satış yapıldı.',
                        'user_id' => auth()->user()->id,
                    ]);
                    $talep->status = TalepStatus::satis;
                    $talep->save();
                } else {
                    $talep = Talep::where('id', $agenda->talep_id)->first();
                    $talep->talepProcesses()->create([
                        'status' => TalepStatus::randevu,
                        'message' => 'Randevu.',
                        'user_id' => auth()->user()->id,
                    ]);
                    $talep->status = TalepStatus::randevu;
                    $talep->save();
                }
            }

            $this->success('Düzenlendi.');
            $this->dispatch('refresh-agendas');
            $this->close();

        } catch (\Throwable $e) {
            $this->error('Tekrar deneyin.');
        }
    }

    public function delete()
    {
        try {
            $agenda_id = $this->occurrence->agenda_id;
            if ($this->occurrence->agenda->talep) {
                $talep = Talep::where('id', $this->occurrence->agenda->talep_id)->first();
                $talep->talepProcesses()->create([
                    'status' => TalepStatus::iptal,
                    'message' => 'Randevu iptal edildi.',
                    'user_id' => auth()->user()->id,
                ]);
                $talep->status = TalepStatus::iptal;
                $talep->save();
            }
            AgendaOccurrence::where('agenda_id', $this->occurrence->agenda_id)->delete();
            Agenda::where('id', $agenda_id)->delete();
            $this->success('Silindi.');
            $this->redirect(route('admin.agenda'));
            $this->close();
        } catch (\Throwable $e) {
            $this->error('Tekrar deneyin.');
        }

    }

    public function render()
    {
        return view('livewire.spotlight.modals.agenda.update-agenda-status');
    }
}
