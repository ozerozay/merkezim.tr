<?php

new class extends Livewire\Volt\Component {
  
};

?>
<div>
    <div class="fixed bottom-6 right-6 z-50">
        <button 
            wire:click="$dispatch('merkezim-spotlight-toggle')"
            class="group relative flex h-14 w-14 items-center justify-center 
                   bg-white dark:bg-gray-800 rounded-2xl shadow-lg 
                   hover:rounded-xl active:scale-95 
                   transition-all duration-300 ease-in-out">
            
            <!-- Arka Plan Efekti -->
            <div class="absolute inset-0.5 rounded-2xl group-hover:rounded-xl 
                       bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 
                       opacity-70 blur transition-all duration-300 
                       group-hover:opacity-100"></div>
            
            <!-- İç Konteyner -->
            <div class="absolute inset-1 rounded-xl group-hover:rounded-lg 
                       bg-white dark:bg-gray-800 
                       transition-all duration-300"></div>
            
            <!-- M İkonu -->
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="relative h-6 w-6 transform 
                        text-gray-700 dark:text-gray-200
                        transition-transform duration-300 
                        group-hover:scale-110 group-hover:rotate-12" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor">
                <path stroke-linecap="round" 
                      stroke-linejoin="round" 
                      stroke-width="2"
                      d="M20 20l-4-16l-4 8l-4-8l-4 16" />
            </svg>
        </button>
    </div>
</div>
