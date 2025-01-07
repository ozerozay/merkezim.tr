<?php

use App\Enum\WebFormType;
use App\Enum\WebFormStatus;
use App\Models\WebForm;
use Livewire\Attributes\Lazy;
use Mary\Traits\Toast;

new
    #[\Livewire\Attributes\Layout('components.layouts.client')]
    #[Lazy()]
    class extends \Livewire\Volt\Component {

        use Toast;

        public $type = 'suggestion';
        public $phone;
        public $email;
        public $text = '';
        public $options;
        public $isLoggedIn;
        public $webForms;

        public function mount()
        {
            $this->isLoggedIn = auth()->check();

            if ($this->isLoggedIn) {
                $this->phone = auth()->user()->phone;
                $this->email = auth()->user()->email;
                $this->webForms = WebForm::where('client_id', auth()->id())
                    ->where('status', '!=', WebFormStatus::REJECTED)
                    ->where('type', WebFormType::OTHER)
                    ->latest()
                    ->take(5)
                    ->get();
            }

            $this->options = [
                [
                    'id' => 'suggestion',
                    'name' => __('contact.subjects.suggestion')
                ],
                [
                    'id' => 'complaint',
                    'name' => __('contact.subjects.complaint')
                ],
                [
                    'id' => 'appointment',
                    'name' => __('contact.subjects.appointment')
                ],
                [
                    'id' => 'other',
                    'name' => __('contact.subjects.other')
                ]
            ];
        }

        public function placeholder()
        {
            return view('livewire.components.card.loading.loading');
        }

        public function submit()
        {
            // Validasyon kurallarını kullanıcı durumuna göre ayarla
            $rules = [
                'type' => 'required',
                'text' => 'required|min:10',
            ];

            // Kullanıcı giriş yapmamışsa iletişim bilgilerini zorunlu tut
            if (!$this->isLoggedIn) {
                $rules['phone'] = 'required';
                $rules['email'] = 'required|email';
            } else {
                // Giriş yapmış kullanıcının bekleyen talep sayısını kontrol et
                $pendingCount = WebForm::where('client_id', auth()->id())
                    ->where('status', WebFormStatus::PENDING)
                    ->count();

                if ($pendingCount >= 2) {
                    $this->warning(__('messages.contact.max_pending'));
                    return;
                }
            }

            $this->validate($rules);

            try {
                WebForm::create([
                    'client_id' => $this->isLoggedIn ? auth()->id() : null,
                    'branch_id' => $this->isLoggedIn ? auth()->user()->branch_id : null,
                    'type' => WebFormType::OTHER,
                    'data' => [
                        'form_type' => $this->type,
                        'phone' => $this->phone,
                        'email' => $this->email,
                        'message' => $this->text,
                        'is_guest' => !$this->isLoggedIn,
                    ],
                ]);

                $this->reset(['type', 'phone', 'email', 'text']);

                $this->success(__('messages.contact.success'));

                // Başarılı kayıttan sonra talep listesini güncelle
                if ($this->isLoggedIn) {
                    $this->webForms = WebForm::where('client_id', auth()->id())
                        ->where('status', '!=', WebFormStatus::REJECTED)
                        ->latest()
                        ->take(5)
                        ->get();
                }
            } catch (\Exception $e) {
                $this->error(__('messages.contact.error'));
            }
        }

        public function cancelRequest($formId)
        {
            try {
                $form = WebForm::where('client_id', auth()->id())
                    ->where('id', $formId)
                    ->where('status', WebFormStatus::PENDING)
                    ->firstOrFail();

                $form->update([
                    'status' => WebFormStatus::REJECTED,
                    'process_note' => __('messages.contact.cancelled_by_user'),
                    'processed_at' => now(),
                ]);

                // Talep listesini güncelle
                $this->webForms = WebForm::where('client_id', auth()->id())
                    ->where('status', '!=', WebFormStatus::REJECTED)
                    ->where('type', WebFormType::OTHER)
                    ->latest()
                    ->take(5)
                    ->get();

                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => __('messages.contact.cancel_success'),
                ]);
            } catch (\Exception $e) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => __('messages.contact.cancel_error'),
                ]);
            }
        }
    };

?>

<div class="relative text-base-content p-2 min-h-[200px]">
    <!-- Loading Indicator -->
    <div wire:loading class="absolute inset-0 bg-base-200/50 backdrop-blur-sm rounded-lg z-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex flex-col items-center gap-2">
                <span class="loading loading-spinner loading-md text-primary"></span>
                <span class="text-sm text-base-content/70">{{ __('common.loading') }}</span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="h-full flex flex-col gap-4">
        <!-- Header Section -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-xl">
                        <i class="text-2xl text-primary">💬</i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">{{ __('contact.title') }}</h2>
                        <p class="text-sm text-base-content/70">{{ __('contact.subtitle') }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <x-button
                        icon="tabler.brand-instagram"
                        :label="__('contact.social.instagram')"
                        external
                        link="https://instagram.com/margeguzellik"
                        class="btn-outline btn-sm"
                        responsive />
                    <x-button
                        icon="tabler.brand-whatsapp"
                        :label="__('contact.social.whatsapp')"
                        external
                        link="https://wa.me/908502411010"
                        class="btn-outline btn-sm"
                        responsive />
                    <x-button
                        icon="tabler.phone"
                        :label="__('contact.social.phone')"
                        external
                        link="tel:+908502411010"
                        class="btn-outline btn-sm"
                        responsive />
                </div>
            </div>
        </div>

        @if($isLoggedIn && $webForms->isNotEmpty())
        <!-- Mevcut Talepler -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-1.5 bg-primary/10 rounded-lg">
                    <i class="text-primary text-lg">📋</i>
                </div>
                <h3 class="font-medium">{{ __('contact.previous_requests') }}</h3>
            </div>

            <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2">
                @foreach($webForms as $form)
                <div class="card bg-base-200/30 p-4 rounded-xl">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="text-sm text-base-content/70">
                                {{ $form->created_at->format('d.m.Y H:i') }}
                            </div>
                            <div class="font-medium">
                                {{ __('contact.subjects.' . $form->data['form_type']) }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div @class([ 'badge badge-sm' , 'badge-warning'=> $form->status->value === WebFormStatus::PENDING->value,
                                'badge-success' => $form->status->value === WebFormStatus::APPROVED->value,
                                ])>
                                {{ __('contact.status.' . $form->status->value) }}
                            </div>
                            @if($form->status->value === WebFormStatus::PENDING->value)
                            <button
                                wire:click="cancelRequest({{ $form->id }})"
                                wire:confirm="{{ __('messages.contact.cancel_confirm') }}"
                                class="btn btn-ghost btn-xs text-error">
                                <i class="text-sm">❌</i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <div class="text-sm text-base-content/70 mt-2 border-t border-base-300 pt-2">
                        <p class="line-clamp-3">{{ $form->data['message'] }}</p>
                    </div>

                    @if($form->process_note)
                    <div class="text-sm mt-2 pt-2 border-t border-base-300">
                        <span class="font-medium text-base-content/80">{{ __('contact.note') }}:</span>
                        <p class="text-base-content/70 line-clamp-2">{{ $form->process_note }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Form Section -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-1.5 bg-primary/10 rounded-lg">
                    <i class="text-primary text-lg">📝</i>
                </div>
                <h3 class="font-medium">{{ __('contact.form.title') }}</h3>
            </div>

            <x-form wire:submit="submit" class="space-y-6">
                <!-- Konu Seçimi -->
                <x-select
                    wire:model="type"
                    :options="$options"
                    :label="__('contact.form.subject')"
                    :placeholder="__('contact.form.select_subject')"
                    option-label="name"
                    option-value="id"
                    class="w-full" />

                <!-- İletişim Bilgileri - Sadece giriş yapmamış kullanıcılar için -->
                @unless($isLoggedIn)
                <div class="grid gap-6 md:grid-cols-2">
                    <livewire:components.form.phone
                        wire:model="phone"
                        :label="__('contact.form.phone')"
                        class="w-full" />
                    <x-input
                        wire:model="email"
                        :label="__('contact.form.email')"
                        type="email"
                        class="w-full" />
                </div>
                @endunless

                <!-- Mesaj -->
                <x-textarea
                    wire:model="text"
                    :label="__('contact.form.message')"
                    :placeholder="__('contact.form.message_placeholder')"
                    rows="5"
                    class="w-full" />

                <!-- Gönder Butonu -->
                <div class="flex justify-end">
                    <x-button
                        type="submit"
                        :label="__('contact.form.submit')"
                        icon="tabler.send"
                        class="btn-primary"
                        spinner />
                </div>
            </x-form>
        </div>
    </div>
</div>