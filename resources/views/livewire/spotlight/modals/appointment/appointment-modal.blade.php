<div class="overflow-x-hidden">
    <x-card title="{{ $appointment->client->name }}"
        subtitle="{{ $appointment->date_start->format('H:i') }} - {{ $appointment->date_end->format('H:i') }}" separator
        progress-indicator>
        <div class="btn-group w-full flex gap-1 mb-3">
            <x-button icon="tabler.phone" class="btn btn-outline flex-grow" external
                link="tel:+90{{ $appointment->client->phone }}">Ara</x-button>
            <x-button icon="tabler.device-mobile-message" class="btn btn-outline flex-grow"
                wire:click="$dispatch('slide-over.open', {component: 'actions.create-send-sms', arguments: {'client': {{ $appointment->client->id }}}})">SMS</x-button>
            <x-button icon="tabler.brand-whatsapp" class="btn btn-outline flex-grow" external
                link="whatsapp://phone=0{{ $appointment->client->phone }}">Mesaj</x-button>
        </div>
        <x-accordion wire:model="group" separator class="bg-base-200">
            <x-collapse name="info">
                <x-slot:heading>
                    <x-icon name="tabler.info-circle" label="Bilgi" />
                </x-slot:heading>
                <x-slot:content>
                    <p>{{ $appointment->serviceNames }}</p>
                    <p>"{{ $appointment->message }}"</p>
                    <x-list-item :item="$appointment">
                        <x-slot:value>
                            TARİH
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $appointment->date->format('d/m/Y') }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$appointment">
                        <x-slot:value>
                            SAAT
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $appointment->date_start->format('H:i') }} - {{ $appointment->date_end->format('H:i') }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$appointment">
                        <x-slot:value>
                            ODA
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $appointment->serviceRoom->name }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$appointment">
                        <x-slot:value>
                            SÜRE
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $appointment->duration }} DK
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$appointment">
                        <x-slot:value>
                            DURUM
                        </x-slot:value>
                        <x-slot:actions>
                            <x-badge class="{{ $appointment->status->color() }}"
                                value="{{ $appointment->status->label() }}" />
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$appointment">
                        <x-slot:value>
                            TOPLAM ÖDEME
                        </x-slot:value>
                        <x-slot:actions>
                            @price($appointment->client->totalDebt())
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$appointment">
                        <x-slot:value>
                            GECİKMİŞ ÖDEME
                        </x-slot:value>
                        <x-slot:actions>
                            @price($appointment->client->totalLateDebt())
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$appointment" class="cursor-pointer">
                        <x-slot:value>
                            +90{{ $appointment->client->phone }}
                        </x-slot:value>
                        <x-slot:actions>
                            <x-button icon="o-phone" external link="tel:+90{{ $appointment->client->phone }}" />
                        </x-slot:actions>
                    </x-list-item>
                    <x-slot:menu>
                        <x-button icon="tabler.x" class="btn-sm btn-outline"
                            wire:click="$dispatch('slide-over.close')" />
                    </x-slot:menu>
                </x-slot:content>
            </x-collapse>
            @if (in_array($appointment->status, $allowFinish))
                <x-collapse name="finish">
                    <x-slot:heading>
                        <x-icon name="o-check" label="Tamamla" />
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="finish">
                            <livewire:components.client.client_service_appointment_dropdown
                                wire:key="sdoe-{{ Str::random(10) }}" :client_id="$appointment->client->id" label="Hizmetler"
                                wire:model="appointmentClientServices" />
                            <livewire:components.form.staff_dropdown wire:key="sdowe-{{ Str::random(10) }}"
                                wire:model="finishUser" />
                            <x-input label="Açıklama" wire:model="messageApprove" />
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="finish" class="btn-primary" />
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
            @endif
            @if (in_array($appointment->status, $allowMerkezde))
                <x-collapse name="merkezde">
                    <x-slot:heading>
                        <x-icon name="tabler.checks" label="Merkezde - Teyitli" />
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="confirmAppointment">
                            <x-select wire:model="merkezdeTeyitliStatus" wire:key="sdccoe-{{ Str::random(10) }}"
                                :options="$merkezdeTeyitliStatuses" />
                            <x-input label="Açıklama" wire:model="messageMerkezde" />
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="confirmAppointment"
                                    class="btn-primary" />
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
            @endif
            @if (in_array($appointment->status, $allowForward))
                <x-collapse name="forward">
                    <x-slot:heading>
                        <x-icon name="tabler.corner-down-right-double" label="Yönlendir" />
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="forwardAppointment">
                            <livewire:components.form.staff_dropdown wire:key="sdocbsae-{{ Str::random(10) }}"
                                wire:model="forwardUser" />
                            <x-input label="Açıklama" wire:model="messageForward" />
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="forwardAppointment"
                                    class="btn-primary" />
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
            @endif
            @if (!in_array($appointment->status, $allowCancel))
                <x-collapse name="cancel">
                    <x-slot:heading>
                        <x-icon name="tabler.x" label="İptal" />
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="cancelAppointment">
                            <x-alert title="Emin misiniz ?"
                                description="İptal ettiğinizde seanslar geri yüklenir ve bu işlem geri alınamaz."
                                icon="o-minus-circle" class="alert-error" />
                            <x-input label="Açıklama" wire:model="messageCancel" />
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="cancelAppointment"
                                    class="btn-primary" />
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
            @endif
            <x-collapse name="past">
                <x-slot:heading>
                    <x-icon name="tabler.history" label="Geçmiş" />
                </x-slot:heading>
                <x-slot:content>
                    @foreach ($appointment->appointmentStatuses as $status)
                        <x-list-item :item="$status" wire:key="sor-{{ Str::random(10) }}" no-separator no-hover>
                            <x-slot:actions>
                                <x-badge value="{{ $status->status->label() }}"
                                    class="badge-{{ $status->status->color() }}" />
                            </x-slot:actions>
                            <x-slot:value>
                                {{ $status->user->name }}
                            </x-slot:value>
                            <x-slot:sub-value>
                                {{ $status->dateHuman }}
                            </x-slot:sub-value>
                        </x-list-item>
                        {{ $status->message }}
                    @endforeach
                </x-slot:content>
            </x-collapse>
        </x-accordion>
        <x-slot:menu>
            <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')" />
        </x-slot:menu>
    </x-card>

</div>
