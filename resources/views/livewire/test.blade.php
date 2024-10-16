<?php

namespace App\Livewire;

use Livewire\Volt\Component;

new class extends Component
{
    public bool $showDrawer3 = false;

    public function ssss()
    {
        $this->showDrawer3 = true;
        //dump('burda');
    }
};
?>
<div>
@php
        $user1 = App\Models\User::inRandomOrder()->first();
        $user2 = App\Models\User::inRandomOrder()->first();
    @endphp
    <x-header title="18 EKİM 2024" separator progress-indicator>
    <x-slot:actions>
        <x-button icon="o-calendar" class="btn-outline" label="Tarih Seç" responsive />
        <x-button icon="tabler.building-store" class="btn-outline" label="Şube Seç" responsive />
        <x-button icon="tabler.filter" class="btn-outline" label="Filtrele" responsive />
        <x-button icon="tabler.sort-descending" class="btn-outline" label="Sırala" responsive />
        <x-button icon="o-plus" class="btn-primary" label="Randevu Oluştur" responsive />
        
    </x-slot:actions>
    </x-header>
    <x-drawer
    wire:model="showDrawer3"
    title="Hello"
    subtitle="Livewire"
    separator
    with-close-button
    close-on-escape
    class="w-11/12 lg:w-1/3"
>
    <div>Hey!</div>
 
    <x-slot:actions>
        <x-button label="Cancel" @click="$wire.showDrawer3 = false" />
        <x-button label="Confirm" class="btn-primary" icon="o-check" />
    </x-slot:actions>
</x-drawer>
    <div class="container mx-auto">
        <div class="shadow-lg rounded-lg">
          
      
          <!-- Günlük Ajanda Grid Yapısı -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- 18 Ekim 2024 -->
             <x-card separator title="Epilasyon 1">
                <x:slot:menu>
                    Kalan: 16
                </x:slot:menu>
             <x-list-item :item="$user1" no-separator class="cursor-pointer" no-hover wire:click='ssss()'>
             
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
               
            </x-list-item>
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-information-circle" class="text-blue-500" wire:click="delete(1)" spinner />
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-warning" /><br />
                    <x-badge value="12:45" class="badge-warning" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-badge value="Gecikti" class="badge-warning" /><br />
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-information-circle" class="text-blue-500" wire:click="delete(1)" spinner />
                </x-slot:actions>
            </x-list-item>
            <x-hr />
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-information-circle" class="text-blue-500" wire:click="delete(1)" spinner />
                </x-slot:actions>
            </x-list-item>
             </x-card>
             <x-card separator title="Epilasyon 1">
                <x:slot:menu>
                    Kalan: 16
                </x:slot:menu>
             <x-list-item :item="$user1" no-separator class="cursor-pointer" no-hover wire:click='ssss()'>
             
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
               
            </x-list-item>
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-information-circle" class="text-blue-500" wire:click="delete(1)" spinner />
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-warning" /><br />
                    <x-badge value="12:45" class="badge-warning" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-badge value="Gecikti" class="badge-warning" /><br />
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-information-circle" class="text-blue-500" wire:click="delete(1)" spinner />
                </x-slot:actions>
            </x-list-item>
            <x-hr />
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-information-circle" class="text-blue-500" wire:click="delete(1)" spinner />
                </x-slot:actions>
            </x-list-item>
             </x-card>
             <x-card separator title="Epilasyon 1">
                <x:slot:menu>
                    Kalan: 16
                </x:slot:menu>
             <x-list-item :item="$user1" no-separator class="cursor-pointer" no-hover wire:click='ssss()'>
             
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
               
            </x-list-item>
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-information-circle" class="text-blue-500" wire:click="delete(1)" spinner />
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-warning" /><br />
                    <x-badge value="12:45" class="badge-warning" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-badge value="Gecikti" class="badge-warning" /><br />
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-information-circle" class="text-blue-500" wire:click="delete(1)" spinner />
                </x-slot:actions>
            </x-list-item>
            <x-hr />
            <x-list-item :item="$user1" no-separator no-hover wire:click="delete(1)">
                <x-slot:avatar>
                    <x-badge value="12:00" class="badge-primary" /><br />
                    <x-badge value="12:45" class="badge-primary" /><br />
                </x-slot:avatar>
                <x-slot:value>
                    CİHAT ÖZER ÖZAY
                </x-slot:value>
                <x-slot:sub-value>
                    (45dk) Hizmet Randevusu 
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-information-circle" class="text-blue-500" wire:click="delete(1)" spinner />
                </x-slot:actions>
            </x-list-item>
             </x-card>
          </div>
        </div>
      </div>
  
</div>