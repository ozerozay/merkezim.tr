<div>
    <x-card title="Danışan Oluştur" separator shadow>
        <x-form wire:submit="save">
            <livewire:components.form.branch_dropdown wire:key="branch-dropdown-{{ Str::random(10) }}"
                wire:model="branch_id" />
            <x-input label="Adı" wire:model="name" />
            <div class="grid grid-cols-2 gap-1">
                <livewire:components.form.phone wire:key="phone-field-{{ Str::random(10) }}" wire:model="phone" />
                <livewire:components.form.date_time label="Doğum Tarihi" wire:model="birth_date"
                    wire:key="date-field-{{ Str::random(10) }}"
                    maxDate="{{ \Carbon\Carbon::now()->addDays(1)->format('Y-m-d') }}" />
            </div>
            <div class="grid grid-cols-2 gap-1">
                <livewire:components.form.gender_dropdown wire:key="gender-dropdown-{{ Str::random(10) }}"
                    wire:model="gender" :gender="1" :includeUniSex="false" />
                <x-input label="TC Kimlik" wire:model="tckimlik" x-mask="99999999999" />
            </div>
            <div class="grid grid-cols-2 gap-1">
                <x-select wire:model.live="il" option-label="il_adi" option-value="id" :options="$this->ils"
                    label="İl" />
                <x-select wire:model="ilce" option-label="ilce_adi" option-value="id" :options="$this->ilces"
                    label="İlçe" />
            </div>
            <x-input label="Adres" wire:model="adres" />
            <x-input label="E-posta" wire:model="email" />
            <x-checkbox wire:model="send_sms" class="self-start">
                <x-slot:label>
                    Danışana SMS gönderimi yapılsın.
                </x-slot:label>
            </x-checkbox>
            <x-checkbox wire:model="can_login" class="self-start">
                <x-slot:label>
                    Online işlem merkezine giriş yapabilsin.
                </x-slot:label>
            </x-checkbox>
            <x-slot:actions>
                <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
