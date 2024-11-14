<?php

use Carbon\Carbon;

new
#[\Livewire\Attributes\Title('Ajanda')]
class extends \Livewire\Volt\Component {

    public $config2;

    public $date = null;

    public $branches = [];

    public function mount(): void
    {
        $this->config2 = \App\Peren::dateConfig(enableTime: false, mode: 'range');
        $this->date = Carbon::now()->format('Y-m-d 00:00') . ' - ' . Carbon::now()->format('Y-m-d 00:00');
    }

    public function filter(): void
    {
        $formatted_date = \App\Peren::formatRangeDate($this->date);
        dump($formatted_date);

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

        dump($branch_ids);
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
        <x-card title="30/08/2018" subtitle="İşlem yok" class="mb-2" separator>
            <x-card subtitle="CİHAT ÖZER ÖZAY -  16:20 - 14:30">
                <x-slot:title>
                    <div class="flex flex-auto items-center">
                        <x-badge class="badge-primary"/>
                        <p class="ml-2">RANDEVU</p>
                    </div>
                </x-slot:title>
                <x-slot:menu>
                    <x-dropdown class="btn-warning">
                        <x-slot:trigger>
                            <x-button icon="tabler.hourglass-empty" class="btn-circle btn-warning"/>
                        </x-slot:trigger>
                        <x-menu-item title="Olumlu"/>
                        <x-menu-item title="Olumsuz"/>
                        <x-menu-item title="Tarih Değiştir"/>
                    </x-dropdown>

                </x-slot:menu>
                "2 kişi gelecek falan oldu filan olduecek falan oldu filan olduecek falan oldu filan olduecek falan oldu
                filan oldu"
            </x-card>
        </x-card>
        <div>
            <x-card title="30/08/2018" subtitle="İşlem yok" class="mb-2" separator>
                <x-card subtitle="CİHAT ÖZER ÖZAY -  16:20 - 14:30">
                    <x-slot:title>
                        <div class="flex flex-auto items-center">
                            <x-badge class="badge-error"/>
                            <p class="ml-2">ÖDEME</p>
                        </div>
                    </x-slot:title>
                    <x-slot:menu>
                        <x-button icon="tabler.x" class="btn-circle btn-error"/>
                    </x-slot:menu>
                    "AYIN 15İNDE MUTLAKA ÖDEMEM LAZIM"
                </x-card>
                <x-hr/>
                <x-card subtitle="16:20 - 14:30">
                    <x-slot:title>
                        <div class="flex flex-auto items-center">
                            <x-badge class="badge-warning"/>
                            <p class="ml-2">HATIRLATMA</p>
                        </div>
                    </x-slot:title>
                    <x-slot:menu>
                        <x-button icon="o-check" class="btn-circle btn-success"/>
                    </x-slot:menu>
                    "MUTLAKA ŞUNU ARAMAM LAZIM"
                </x-card>
            </x-card>
        </div>

        <x-card title="30/08/2018" subtitle="İşlem yok" class="mb-2" separator>
            <x-card subtitle="CİHAT ÖZER ÖZAY -  16:20 - 14:30">
                <x-slot:title>
                    <div class="flex flex-auto items-center">
                        <x-badge class="badge-error"/>
                        <p class="ml-2">ÖDEME</p>
                    </div>
                </x-slot:title>
                <x-slot:menu>
                    <x-button icon="tabler.x" class="btn-circle btn-error"/>
                </x-slot:menu>
                "AYIN 15İNDE MUTLAKA ÖDEMEM LAZIM"
            </x-card>
            <x-hr/>
            <x-card subtitle="16:20 - 14:30">
                <x-slot:title>
                    <div class="flex flex-auto items-center">
                        <x-badge class="badge-warning"/>
                        <p class="ml-2">HATIRLATMA</p>
                    </div>
                </x-slot:title>
                <x-slot:menu>
                    <x-button icon="o-check" class="btn-circle btn-success"/>
                </x-slot:menu>
                "MUTLAKA ŞUNU ARAMAM LAZIM"
            </x-card>
        </x-card>
        <x-card title="30/08/2018" subtitle="İşlem yok" class="mb-2" separator>
            <x-card subtitle="CİHAT ÖZER ÖZAY -  16:20 - 14:30">
                <x-slot:title>
                    <div class="flex flex-auto items-center">
                        <x-badge class="badge-error"/>
                        <p class="ml-2">ÖDEME</p>
                    </div>
                </x-slot:title>
                <x-slot:menu>
                    <x-button icon="tabler.x" class="btn-circle btn-error"/>
                </x-slot:menu>
                "AYIN 15İNDE MUTLAKA ÖDEMEM LAZIM"
            </x-card>
            <x-hr/>
            <x-card subtitle="16:20 - 14:30">
                <x-slot:title>
                    <div class="flex flex-auto items-center">
                        <x-badge class="badge-warning"/>
                        <p class="ml-2">HATIRLATMA</p>
                    </div>
                </x-slot:title>
                <x-slot:menu>
                    <x-button icon="o-check" class="btn-circle btn-success"/>
                </x-slot:menu>
                "MUTLAKA ŞUNU ARAMAM LAZIM"
            </x-card>
        </x-card>
        <x-card title="30/08/2018" subtitle="İşlem yok" class="mb-2" separator>
            <x-card subtitle="CİHAT ÖZER ÖZAY -  16:20 - 14:30">
                <x-slot:title>
                    <div class="flex flex-auto items-center">
                        <x-badge class="badge-primary"/>
                        <p class="ml-2">RANDEVU</p>
                    </div>
                </x-slot:title>
                <x-slot:menu>
                    <x-button icon="tabler.hourglass-empty" class="btn-circle btn-warning"/>
                </x-slot:menu>
                "2 kişi gelecek falan oldu filan olduecek falan oldu filan olduecek falan oldu filan olduecek falan oldu
                filan oldu"
            </x-card>
            <x-hr/>
            <x-card subtitle="CİHAT ÖZER ÖZAY -  16:20 - 14:30">
                <x-slot:title>
                    <div class="flex flex-auto items-center">
                        <x-badge class="badge-primary"/>
                        <p class="ml-2">RANDEVU</p>
                    </div>
                </x-slot:title>
                <x-slot:menu>
                    <x-button icon="tabler.hourglass-empty" class="btn-circle btn-warning"/>
                </x-slot:menu>
                "2 kişi gelecek falan oldu filan olduecek falan oldu filan olduecek falan oldu filan olduecek falan oldu
                filan oldu"
            </x-card>
            <x-hr/>
            <x-card subtitle="CİHAT ÖZER ÖZAY -  16:20 - 14:30">
                <x-slot:title>
                    <div class="flex flex-auto items-center">
                        <x-badge class="badge-primary"/>
                        <p class="ml-2">RANDEVU</p>
                    </div>
                </x-slot:title>
                <x-slot:menu>
                    <x-button icon="tabler.hourglass-empty" class="btn-circle btn-warning"/>
                </x-slot:menu>
                "2 kişi gelecek falan oldu filan olduecek falan oldu filan olduecek falan oldu filan olduecek falan oldu
                filan oldu"
            </x-card>
            <x-hr/>
        </x-card>

    </div>
    <x-card title="30/08/2018" separator>

    </x-card>

</div>
