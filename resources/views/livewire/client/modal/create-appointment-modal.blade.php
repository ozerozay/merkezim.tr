<div>
    <div class="overflow-x-hidden">
        <x-card title="Randevu Oluştur" subtitle="Saniyeler içerisinde randevu oluşturun" separator progress-indicator>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')"/>
            </x-slot:menu>
            <div wire:loading.remove x-data="{ step: @entangle('step') }" class="w-full max-w-2xl mx-auto space-y-8">
                <!-- Step Progress -->
                <div class="flex items-center justify-between">
                    <template
                        x-for="(title, index) in ['Randevu', 'Şube', 'Kategori', 'Hizmet', 'Tarih', 'Tamam']"
                        :key="index">
                        <div class="flex-1 flex flex-col items-center">
                            <div
                                :class="{'bg-blue-600 text-white': step === index + 1, 'bg-gray-300 text-gray-700': step !== index + 1}"
                                class="w-8 h-8 flex items-center justify-center rounded-full mb-2">
                                <span x-text="index + 1"></span>
                            </div>
                            <p class="text-sm" x-text="title"></p>
                        </div>
                    </template>
                </div>

                <!-- Step 1: Randevu Türü Seçimi -->
                <div x-show="step === 1" class="space-y-4">
                    <h1 class="text-2xl font-semibold text-center">Randevu Alma Seçenekleri</h1>
                    <label class="block cursor-pointer">
                        <div @click="$wire.selectAppointmentType('date')"
                             class="p-4 border rounded-lg hover:shadow transition">
                            Tarih ve Saat Seçerek
                        </div>
                    </label>
                    <label class="block cursor-pointer">
                        <div @click="$wire.selectAppointmentType('range')"
                             class="p-4 border rounded-lg hover:shadow transition">
                            Tarih Aralığı Girerek
                        </div>
                    </label>
                    <label class="block cursor-pointer">
                        <div @click="$wire.selectAppointmentType('multi')"
                             class="p-4 border rounded-lg hover:shadow transition">
                            Birden Fazla Tarih Seçerek
                        </div>
                    </label>
                </div>

                <!-- Step 2: Şube Seçimi -->
                <div x-show="step === 2" class="space-y-4">
                    <h1 class="text-2xl font-semibold text-center">Şube Seçimi</h1>
                    <div class="mb-4">
                        @foreach($this->branches as $branch)
                            <label class="block cursor-pointer mt-2">
                                <div @click="$wire.selectBranch({{ $branch->id }})"
                                     class="p-4 border rounded-lg hover:shadow transition">
                                    {{ $branch->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Step 3: Hizmet Kategorisi Seçimi -->
                <div x-show="step === 3" class="space-y-4">
                    <h1 class="text-2xl font-semibold text-center">Hizmet Kategorisi Seçimi</h1>
                    @foreach($this->serviceCategories as $category)
                        <label class="block cursor-pointer mt-2">
                            <div @click="$wire.selectCategory({{ $category->id }})"
                                 class="p-4 border rounded-lg hover:shadow transition">
                                {{ $category->name }}
                            </div>
                        </label>
                    @endforeach
                </div>

                <!-- Step 4: Hizmet Seçimi -->
                <div x-show="step === 4" class="space-y-4">
                    <h1 class="text-2xl font-semibold text-center">Hizmet Seçimi</h1>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach ($services as $service)
                            <label class="block cursor-pointer mt-2">
                                <div class="relative flex items-center p-4 border rounded-lg hover:shadow transition">

                                    <!-- Kalan Seans Badge Sağ Üst -->
                                    <span
                                        class="absolute top-2 right-2 bg-blue-500 text-white text-xs py-1 px-2 rounded-full">Kalan Seans: {{ $service->remaining }}
                                    </span>
                                    <x-checkbox
                                        wire:model="selectedServices.{{ $service->service->id }}"
                                        wire:key="cxc-{{ Str::random(10) }}"
                                        :label="''"
                                        class="ml-2"
                                    />
                                    <span class="ml-8">{{ $service->service->name }} - {{ $service->service->duration }} dakika</span>
                                </div>
                            </label>
                        @endforeach
                        <x-button class="btn-primary" wire:click="selectService">Devam</x-button>

                    </div>
                </div>

                <div x-show="step === 5" class="space-y-4">
                    <h1 class="text-2xl font-semibold text-center">Randevu Tarihi Seçimi</h1>

                    @if (!empty($available_appointments_range))
                        <div class="p-3 border rounded-lg ">
                            <x-select-group :options="$available_appointments_range"
                                            wire:model="selected_available_date"
                                            label="Boş Tarihler"/>

                        </div>
                        <x-textarea label="Randevu notunuz" wire:model="messageAppointment"/>
                        <x-button class="btn-primary btn-block mt-2" wire:click="createAppointmentMulti">
                            RANDEVU OLUŞTUR
                        </x-button>
                    @else
                        <div class="p-3 border rounded-lg ">
                            @if ($appointmentType == 'date')
                                <livewire:components.form.date_time
                                    label="Tarih Seçin"
                                    minDate="{{ date('Y-m-d') }}"
                                    wire:model="selectedDate"
                                    :enableTime="true"
                                    wire:key="xvk-{{Str::random()}}"/>
                                <x-button class="btn-primary btn-block mt-2" wire:click="createAppointmentManuel">
                                    Randevu
                                    Oluştur
                                </x-button>
                            @elseif ($appointmentType == 'range')
                                <livewire:components.form.date_time
                                    label="Tarih Seçin"
                                    minDate="{{ date('Y-m-d') }}"
                                    wire:model="selectedDate"
                                    mode="range"
                                    wire:key="xvk-{{Str::random()}}"/>
                                <x-button class="btn-primary btn-block mt-2" wire:click="createAppointmentRange">
                                    Boşlukları
                                    Göster
                                </x-button>
                            @elseif($appointmentType == 'multi')
                                <livewire:components.form.date_time
                                    label="Tarih Seçin"
                                    minDate="{{ date('Y-m-d') }}"
                                    wire:model="selectedDate"
                                    mode="multiple"
                                    wire:key="xvk-{{Str::random()}}"/>
                                <x-button class="btn-primary btn-block mt-2" wire:click="createAppointmentMulti">
                                    Boşlukları
                                    Göster
                                </x-button>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Step 6: Tamam -->
                <div x-show="step === 6" class="space-y-4">
                    <h1 class="text-2xl font-semibold text-center">Tamam</h1>
                    <p class="text-center">Randevu tamamlandı. Şimdi ilerleyebilirsiniz.</p>
                </div>


                <!-- Navigation Buttons -->
                @if (1==2)
                    <div class="flex justify-between">
                        <button @click="$wire.previousStep()" :disabled="step === 1"
                                class="bg-gray-300 text-gray-800 py-2 px-4 rounded hover:bg-gray-400 disabled:opacity-50">
                            Geri
                        </button>
                        <template x-if="step < 6">
                            <button @click="$wire.nextStep()"
                                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                                İleri
                            </button>
                        </template>
                        <template x-if="step === 6">
                            <button @click="$wire.complete()"
                                    class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                                Tamam
                            </button>
                        </template>
                    </div>
                @endif
            </div>
            <div wire:loading class="flex items-center justify-center">
                <div class="text-center">
                    <svg class="animate-spin h-10 w-10 text-primary mx-auto" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <p class="mt-4 text-primary font-semibold">Lütfen Bekleyin...</p>
                </div>
            </div>
        </x-card>
    </div>
</div>
