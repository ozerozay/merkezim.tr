<div class="overflow-x-hidden">
    <x-card title="{{ $talep->name ?? '' }}" subtitle="{{ $talep->phone ?? '' }}" separator progress-indicator>
        <div class="btn-group w-full flex gap-1 mb-3">
            <x-button icon="tabler.phone" class="btn btn-outline flex-grow" external
                      link="tel:+90{{ $talep->phone }}">Ara
            </x-button>
            <x-button icon="tabler.device-mobile-message" class="btn btn-outline flex-grow">SMS</x-button>
            <x-button icon="tabler.brand-whatsapp" class="btn btn-outline flex-grow" external
                      link="whatsapp://phone=0{{ $talep->phone }}">Mesaj
            </x-button>
        </div>
        <x-accordion wire:model="group" separator class="bg-base-200">
            <x-collapse name="info">
                <x-slot:heading>
                    <x-icon name="tabler.info-circle" label="Bilgi"/>
                </x-slot:heading>
                <x-slot:content>
                    <x-list-item :item="$talep">
                        <x-slot:value>
                            TARİH
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $talep->date->format('d/m/Y') }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$talep">
                        <x-slot:value>
                            TELEFON
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $talep->phone }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$talep">
                        <x-slot:value>
                            TELEFON
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $talep->phone_long ?? '-' }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$talep">
                        <x-slot:value>
                            ÇEŞİT
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $talep->type?->label() }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$talep">
                        <x-slot:value>
                            DURUM
                        </x-slot:value>
                        <x-slot:actions>
                            <x-badge class="{{ $talep->status->color() }}" value="{{ $talep->status->label() }}"/>
                        </x-slot:actions>
                    </x-list-item>
                    <x-slot:menu>
                        <x-button icon="tabler.x" class="btn-sm btn-outline"
                                  wire:click="$dispatch('slide-over.close')"/>
                    </x-slot:menu>
                </x-slot:content>
            </x-collapse>
            <x-collapse name="addprocess">
                <x-slot:heading>
                    <x-icon name="o-plus" label="İşlem"/>
                </x-slot:heading>
                <x-slot:content>
                    <x-form wire:submit="addProcess">
                        <x-select label="İşlem" wire:key="fgfdg-{{ Str::random(10) }}" wire:model="statusProcess"
                                  :options="$talepProcessList"/>
                        <x-input label="Açıklama" wire:model="messageProcess"/>
                        <x-slot:actions>
                            <x-button label="Gönder" type="submit" spinner="addProcess" class="btn-primary"/>
                        </x-slot:actions>
                    </x-form>

                </x-slot:content>
            </x-collapse>
            <x-collapse name="randevu">
                <x-slot:heading>
                    <x-icon name="o-calendar" label="Randevu - Sonra Ara"/>
                </x-slot:heading>
                <x-slot:content>
                    <x-form wire:submit="addAppointment">
                        <x-select label="İşlem" wire:model="statusProcessRandevu" :options="$talepProcessRandevuList"/>
                        <livewire:components.form.date_time wire:key="date-fi2eld-{{ Str::random(10) }}" label="Tarih"
                                                            wire:model="randevuDate" :enableTime="true"
                                                            minDate="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"/>
                        <x-input label="Açıklama" wire:model="messageProcessRandevu"/>
                        <x-slot:actions>
                            <x-button label="Gönder" type="submit" spinner="addAppointment" class="btn-primary"/>
                        </x-slot:actions>
                    </x-form>
                </x-slot:content>
            </x-collapse>
            <x-collapse name="edit">
                <x-slot:heading>
                    <x-icon name="o-pencil" label="Düzenle"/>
                </x-slot:heading>
                <x-slot:content>
                    <x-form wire:submit="edit">
                        <x-input wire:model="name" label="Ad"/>
                        <livewire:components.form.phone wire:key="dsfr-{{ Str::random(10) }}" wire:model="phone"/>
                        <livewire:components.form.talep_type_dropdown wire:key="dsfrfd-{{ Str::random(10) }}"
                                                                      wire:model="type"/>
                        <x-input label="Açıklama" wire:model="message"/>
                        <x-slot:actions>
                            <x-button label="Gönder" type="submit" spinner="edit" class="btn-primary"/>
                        </x-slot:actions>
                    </x-form>
                </x-slot:content>
            </x-collapse>
            <x-collapse name="past">
                <x-slot:heading>
                    <x-icon name="tabler.history" label="Geçmiş"/>
                </x-slot:heading>
                <x-slot:content>
                    @foreach ($talep->talepProcesses as $process)
                        <x-list-item :item="$process" no-separator no-hover>
                            <x-slot:actions>
                                <x-badge value="{{ $process->status->label() }}"
                                         class="badge-{{ $process->status->color() }}"/>
                            </x-slot:actions>
                            <x-slot:value>
                                {{ $process->user->name }}
                            </x-slot:value>
                            <x-slot:sub-value>
                                {{ $process->dateHuman }}
                            </x-slot:sub-value>
                        </x-list-item>
                        {{ $process->message }}
                    @endforeach
                </x-slot:content>
            </x-collapse>

        </x-accordion>
    </x-card>
</div>
