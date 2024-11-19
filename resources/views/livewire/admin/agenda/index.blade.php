<?php

use Carbon\Carbon;

new
#[\Livewire\Attributes\Title('Ajanda')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    public $config2;

    public $date = null;

    public $branches = [];

    public $agendas;

    public function mount(): void
    {
        $this->config2 = \App\Peren::dateConfig(enableTime: false, mode: 'range');
        $this->date = Carbon::now()->startOfMonth()->format('Y-m-d 00:00') . ' - ' . Carbon::now()->endOfMonth()->format('Y-m-d 00:00');
        $this->getAgendas(\App\Peren::formatRangeDate($this->date), auth()->user()->staff_branches);
    }

    public function filter(): void
    {
        $formatted_date = \App\Peren::formatRangeDate($this->date);

        $branch_ids = [];

        foreach ($this->branches as $key => $branch) {
            if ($branch['checked']) {
                $branch_ids[] = $key;
            }
        }

        if (count($branch_ids) < 1) {
            $this->error('Şube seçmelisiniz.');

            return;
        }

        $this->getAgendas($formatted_date, $branch_ids);
    }

    public function getAgendas($date, $branch): void
    {
        $this->agendas = \App\Models\AgendaOccurrence::query()
            ->whereRaw('occurrence_date <= DATE(?) and occurrence_date >= DATE(?)', [$date['last_date'], $date['first_date']])
            ->whereHas('agenda', function ($q) use ($branch) {
                $q->whereIn('branch_id', $branch);
            })
            ->with('agenda')
            ->get();
    }

};

?>
<div>
    <x-header title="Ajanda" separator progress-indicator>
        <x-slot:actions>
            <x-dropdown label="Tarih">
                <x-slot:trigger>
                    <x-button icon="tabler.filter" class="btn-outline" label="Filtrele" responsive/>
                </x-slot:trigger>
                <x-form wire:submit="filter">
                    <x-menu-item @click.stop="">
                        <x-datepicker label="Tarih" wire:model="date" icon="o-calendar" :config="$config2" inline/>
                    </x-menu-item>
                    <x-menu-separator/>
                    @foreach(auth()->user()->staff_branch as $branch)
                        <x-menu-item @click.stop="">
                            <x-checkbox wire:model="branches.{{ $branch->id }}.checked" label="{{ $branch->name }}"/>
                        </x-menu-item>
                    @endforeach
                    <x:slot:actions>
                        <x-button class="btn-outline" type="submit" label="Filtrele"/>
                    </x:slot:actions>
                </x-form>
            </x-dropdown>
            <x-dropdown label="Oluştur" class="btn-primary" right>
                <x-menu-item title="Randevu" link="{{ route('admin.actions.create_reminder') }}"/>
                <x-menu-item title="Hatırlatma" link="{{ route('admin.actions.create_reminder') }}"/>
                <x-menu-item title="Ödeme Takip" link="{{ route('admin.actions.create_payment_tracking') }}"/>
            </x-dropdown>
        </x-slot:actions>
    </x-header>
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5 mb-5">
        @foreach($agendas->groupBy('occurrence_date') as $key=>$agendaItems)
            <x-card title="{{ Carbon::createFromFormat('Y-m-d H:i:s', $key)->format('d/m/Y') }}" class="mb-2"
                    separator>
                @foreach($agendaItems as $agendaItem)
                    @if ($agendaItem->agenda->type == \App\AgendaType::appointment)
                        <livewire:components.card.agenda.card_agenda_appointment :item="$agendaItem"
                                                                                 wire:key="{{$agendaItem->id}}"/>
                    @elseif ($agendaItem->agenda->type == \App\AgendaType::reminder)
                        <livewire:components.card.agenda.card_agenda_reminder :item="$agendaItem"
                                                                              wire:key="{{$agendaItem->id}}"/>
                    @elseif ($agendaItem->agenda->type == \App\AgendaType::payment)
                        <livewire:components.card.agenda.card_agenda_payment_tracking :item="$agendaItem"
                                                                                      wire:key="{{$agendaItem->id}}"/>
                    @endif
                @endforeach
            </x-card>
        @endforeach
    </div>
</div>
